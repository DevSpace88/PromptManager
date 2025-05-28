<?php

namespace App\Services;

use App\Models\ExecutionLog;
use App\Models\Workflow;
use App\Models\Prompt;
use App\Services\AIService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WorkflowExecutionService
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Execute a workflow based on an execution log
     */
    public function execute(ExecutionLog $executionLog)
    {
        try {
            // Update log status
            $executionLog->update([
                'status' => 'running',
            ]);

            // Get the workflow
            $workflow = $executionLog->workflow;

            // Prepare variables storage
            $variables = $executionLog->input_data ?? [];

            // Get starting nodes (nodes without incoming edges)
            $startingNodes = $this->getStartingNodes($workflow);

            // Execute the workflow
            $nodeResults = [];
            foreach ($startingNodes as $nodeId) {
                $this->executeNode($nodeId, $workflow, $variables, $nodeResults);
            }

            // Update log with results
            $executionLog->update([
                'status' => 'completed',
                'node_results' => $nodeResults,
                'output_data' => $variables, // Store final variables
                'completed_at' => now(),
            ]);

            return [
                'success' => true,
                'variables' => $variables,
                'node_results' => $nodeResults,
            ];
        } catch (\Exception $e) {
            Log::error('Workflow execution error', [
                'workflow_id' => $executionLog->workflow_id,
                'execution_id' => $executionLog->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Update log with error
            $executionLog->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get starting nodes (nodes without incoming edges)
     */
    protected function getStartingNodes(Workflow $workflow)
    {
        $nodes = collect($workflow->nodes);
        $edges = collect($workflow->edges);

        // Get all target nodes (nodes that have incoming edges)
        $targetNodeIds = $edges->pluck('target')->unique()->toArray();

        // Starting nodes are nodes that don't have incoming edges
        return $nodes->pluck('id')->filter(function ($nodeId) use ($targetNodeIds) {
            return !in_array($nodeId, $targetNodeIds);
        })->toArray();
    }

    /**
     * Get next nodes (nodes connected by outgoing edges)
     */
    protected function getNextNodes(string $nodeId, Workflow $workflow)
    {
        $edges = collect($workflow->edges);

        // Get all edges where this node is the source
        $outgoingEdges = $edges->filter(function ($edge) use ($nodeId) {
            return $edge['source'] === $nodeId;
        });

        // Return the target nodes
        return $outgoingEdges->pluck('target')->toArray();
    }

    /**
     * Execute a node in the workflow
     */
    protected function executeNode(string $nodeId, Workflow $workflow, array &$variables, array &$nodeResults)
    {
        // Find the node
        $node = collect($workflow->nodes)->firstWhere('id', $nodeId);

        if (!$node) {
            throw new \Exception("Node not found: {$nodeId}");
        }

        // Skip if already executed
        if (isset($nodeResults[$nodeId])) {
            // If the node was already processed (e.g. by a different path leading to it),
            // we still need to ensure its subsequent nodes are processed if it's not a terminal type of node.
            // However, condition nodes manage their own subsequent execution path.
            // Other nodes might have multiple inputs leading to them but should only execute once.
            // Their output is already in $variables and $nodeResults.
            // We only need to trigger their next nodes if they haven't been triggered by this path yet.
            // This recursive call structure handles multiple paths and merging.
            // For simplicity in this step, we return. A more sophisticated graph traversal might be needed for complex loops or re-executions.
            return $nodeResults[$nodeId];
        }

        Log::info("Executing node: {$node['id']} of type: {$node['type']}");

        // Process the node based on its type
        $result = match($node['type']) {
            'prompt' => $this->executePromptNode($node, $variables),
            'condition' => $this->executeConditionNode($node, $variables, $workflow, $nodeResults), // Condition node handles its own branching
            'input' => $this->executeInputNode($node, $variables),
            'output' => $this->executeOutputNode($node, $variables),
            'api' => $this->executeApiNode($node, $variables),
            'transform' => $this->executeTransformNode($node, $variables),
            'scraper' => $this->executeScraperNode($node, $variables), // Added scraper type
            'scraperNode' => $this->executeScraperNode($node, $variables), // Added scraperNode as well for flexibility
            default => throw new \Exception("Unsupported node type: {$node['type']}"),
        };

        // Store the result
        $nodeResults[$nodeId] = $result;
        Log::info("Node {$node['id']} executed", ['result' => $result]);


        // Process next nodes (if not a condition node, which handles its own flow)
        // Condition nodes handle their own subsequent execution based on the condition outcome.
        if ($node['type'] !== 'condition') {
            $nextNodes = $this->getNextNodes($nodeId, $workflow);
            foreach ($nextNodes as $nextNodeId) {
                 Log::info("Queueing next node: {$nextNodeId} from {$node['id']}");
                $this->executeNode($nextNodeId, $workflow, $variables, $nodeResults);
            }
        }

        return $result;
    }

    /**
     * Execute a prompt node
     */
    protected function executePromptNode(array $node, array &$variables)
    {
        $data = $node['data'] ?? [];

        // If this is a reference to a saved prompt
        if (isset($data['prompt_id'])) {
            $prompt = Prompt::findOrFail($data['prompt_id']);
            $promptVersion = $prompt->currentVersion();
            $content = $promptVersion->content;
        } else {
            $content = $data['content'] ?? '';
        }

        // Replace variables in content
        foreach ($variables as $key => $value) {
            if (is_string($value)) {
                $content = str_replace("{{" . $key . "}}", $value, $content);
            }
        }

        // Get AI provider settings
        $provider = $data['provider'] ?? 'openai';
        $model = $data['model'] ?? 'gpt-4';
        $temperature = $data['temperature'] ?? 0.7;
        $maxTokens = $data['max_tokens'] ?? 2000;

        // Call AI service
        $aiResponse = $this->aiService->generateCompletion(
            $provider,
            $model,
            $content,
            $temperature,
            $maxTokens
        );

        if ($aiResponse['error']) {
            throw new \Exception("AI service error: " . $aiResponse['message']);
        }

        // Store result in variables
        $outputVariable = $data['output_variable'] ?? 'result';
        $variables[$outputVariable] = $aiResponse['text'];

        return [
            'success' => true,
            'output' => $aiResponse['text'],
            'output_variable' => $outputVariable,
        ];
    }

    /**
     * Execute a condition node
     */
    protected function executeConditionNode(array $node, array &$variables, Workflow $workflow, array &$nodeResults)
    {
        $data = $node['data'] ?? [];
        $condition = $data['condition'] ?? '';

        // Replace variables in condition
        foreach ($variables as $key => $value) {
            if (is_string($value)) {
                $condition = str_replace("{{" . $key . "}}", "'" . addslashes($value) . "'", $condition);
            }
        }

        // Evaluate condition safely
        $conditionMet = false;
        try {
            // Use eval cautiously - this should be replaced with a safer parser
            $conditionMet = eval("return {$condition};");
        } catch (\Throwable $e) {
            throw new \Exception("Condition evaluation error: " . $e->getMessage());
        }

        // Get true and false paths
        $truePath = $data['true_path'] ?? null;
        $falsePath = $data['false_path'] ?? null;

        // Follow the appropriate path
        if ($conditionMet && $truePath) {
            $this->executeNode($truePath, $workflow, $variables, $nodeResults);
        } elseif (!$conditionMet && $falsePath) {
            $this->executeNode($falsePath, $workflow, $variables, $nodeResults);
        }

        return [
            'success' => true,
            'condition' => $condition,
            'condition_met' => $conditionMet,
            'path_taken' => $conditionMet ? 'true' : 'false',
        ];
    }

    /**
     * Execute an input node
     */
    protected function executeInputNode(array $node, array &$variables)
    {
        $data = $node['data'] ?? [];
        $inputVariable = $data['variable'] ?? '';

        // Input nodes just pass through already set variables
        // or set default values if provided
        if (!isset($variables[$inputVariable]) && isset($data['default_value'])) {
            $variables[$inputVariable] = $data['default_value'];
        }

        return [
            'success' => true,
            'variable' => $inputVariable,
            'value' => $variables[$inputVariable] ?? null,
        ];
    }

    /**
     * Execute an output node
     */
    protected function executeOutputNode(array $node, array &$variables)
    {
        $data = $node['data'] ?? [];
        $outputVariables = $data['variables'] ?? [];

        $output = [];
        foreach ($outputVariables as $variable) {
            $output[$variable] = $variables[$variable] ?? null;
        }

        return [
            'success' => true,
            'output' => $output,
        ];
    }

    /**
     * Execute an API node
     */
    protected function executeApiNode(array $node, array &$variables)
    {
        $data = $node['data'] ?? [];
        $url = $data['url'] ?? '';
        $method = $data['method'] ?? 'GET';
        $headers = $data['headers'] ?? [];
        $body = $data['body'] ?? null;

        // Replace variables in URL, headers and body
        foreach ($variables as $key => $value) {
            if (is_string($value)) {
                $url = str_replace("{{" . $key . "}}", urlencode($value), $url);

                // Replace in headers
                foreach ($headers as $headerKey => $headerValue) {
                    $headers[$headerKey] = str_replace("{{" . $key . "}}", $value, $headerValue);
                }

                // Replace in body if it's a string
                if (is_string($body)) {
                    $body = str_replace("{{" . $key . "}}", $value, $body);
                }
            }
        }

        // Make API call
        try {
            $request = Http::withHeaders($headers);

            $response = match(strtoupper($method)) {
                'GET' => $request->get($url),
                'POST' => $request->post($url, $body),
                'PUT' => $request->put($url, $body),
                'PATCH' => $request->patch($url, $body),
                'DELETE' => $request->delete($url, $body),
                default => throw new \Exception("Unsupported HTTP method: {$method}"),
            };

            // Store result in variables
            $outputVariable = $data['output_variable'] ?? 'api_result';
            $variables[$outputVariable] = $response->json() ?: $response->body();

            return [
                'success' => true,
                'status_code' => $response->status(),
                'response' => $variables[$outputVariable],
                'output_variable' => $outputVariable,
            ];
        } catch (\Exception $e) {
            throw new \Exception("API call error: " . $e->getMessage());
        }
    }

    /**
     * Execute a transform node
     */
    protected function executeTransformNode(array $node, array &$variables)
    {
        $data = $node['data'] ?? [];
        $inputVariable = $this->replacePlaceholders($data['input_variable'] ?? '', $variables);
        $outputVariable = $data['output_variable'] ?? 'transformed_result';
        $transformation = $data['transformation'] ?? 'json_parse';
        $regex = $data['regex'] ?? '';
        $code = $data['code'] ?? '';

        $inputValue = $variables[$inputVariable] ?? null;

        if ($inputValue === null && $transformation !== 'custom_code') { // Custom code might not need input
            Log::warning("TransformNode: Input variable '{$inputVariable}' not found or is null.", ['node_id' => $node['id']]);
            // Allow to continue, result will be null or default based on transformation
        }
        
        $transformedValue = null;
        $success = true;
        $errorMessage = null;

        try {
            switch ($transformation) {
                case 'json_parse':
                    $transformedValue = $this->transformJsonParse($inputValue);
                    break;
                case 'json_stringify':
                    $transformedValue = $this->transformJsonStringify($inputValue);
                    break;
                case 'to_uppercase':
                    $transformedValue = $this->transformToUppercase($inputValue);
                    break;
                case 'to_lowercase':
                    $transformedValue = $this->transformToLowercase($inputValue);
                    break;
                case 'trim':
                    $transformedValue = $this->transformTrim($inputValue);
                    break;
                case 'extract_text':
                    $transformedValue = $this->transformExtractText($inputValue, $regex);
                    break;
                case 'custom_code':
                    // Note: Custom code execution needs careful sandboxing in a real app
                    $transformedValue = $this->transformCustomCode($inputValue, $code, $variables);
                    break;
                default:
                    throw new \Exception("Unsupported transformation: {$transformation}");
            }
        } catch (\Exception $e) {
            Log::error("TransformNode: Error during transformation '{$transformation}'.", [
                'node_id' => $node['id'],
                'error' => $e->getMessage()
            ]);
            $success = false;
            $errorMessage = $e->getMessage();
            $transformedValue = ['error' => $errorMessage]; // Store error in output
        }

        $variables[$outputVariable] = $transformedValue;

        return [
            'success' => $success,
            'output' => $transformedValue,
            'output_variable' => $outputVariable,
            'error' => $errorMessage,
        ];
    }

    /**
     * Execute a scraper node
     */
    protected function executeScraperNode(array $node, array &$variables)
    {
        $nodeData = $node['data'] ?? [];
        $outputVariable = $nodeData['output_variable'] ?? 'scraped_data';
        $nodeId = $node['id'];

        Log::info("Executing ScraperNode: {$nodeId}", ['data' => $nodeData]);

        try {
            $processedUrl = $this->replacePlaceholders($nodeData['url'] ?? '', $variables);
            $containerSelector = $this->replacePlaceholders($nodeData['container_selector'] ?? '', $variables);
            
            // Ensure field_selectors is an array and process placeholders within selectors
            $fieldSelectors = $nodeData['field_selectors'] ?? [];
            if (!is_array($fieldSelectors)) $fieldSelectors = []; // Default to empty array if not set properly

            $processedFieldSelectors = [];
            foreach ($fieldSelectors as $fs) {
                if (isset($fs['name']) && isset($fs['selector'])) {
                    $processedFieldSelectors[] = [
                        'name' => $fs['name'], // Name usually doesn't need placeholder replacement
                        'selector' => $this->replacePlaceholders($fs['selector'], $variables)
                    ];
                }
            }

            $linkFieldName = $nodeData['link_field_name'] ?? null;
            $linkSelector = isset($nodeData['link_selector']) ? $this->replacePlaceholders($nodeData['link_selector'], $variables) : null;


            if (empty($processedUrl) || empty($containerSelector) || empty($processedFieldSelectors)) {
                $errorMessage = 'ScraperNode configuration incomplete. URL, Container Selector, and Field Selectors are required and must resolve correctly.';
                Log::warning($errorMessage, ['node_id' => $nodeId, 'url' => $processedUrl, 'container' => $containerSelector, 'fields' => $processedFieldSelectors]);
                $variables[$outputVariable] = ['error' => $errorMessage];
                return ['success' => false, 'error' => $errorMessage, 'output_variable' => $outputVariable];
            }
            
            $scraperServiceUrl = env('SCRAPER_SERVICE_URL');
            if (!$scraperServiceUrl) {
                $errorMessage = 'Scraper service URL (SCRAPER_SERVICE_URL) is not configured in .env.';
                Log::error($errorMessage, ['node_id' => $nodeId]);
                $variables[$outputVariable] = ['error' => $errorMessage];
                return ['success' => false, 'error' => $errorMessage, 'output_variable' => $outputVariable];
            }

            $payload = [
                'url' => $processedUrl,
                'containerSelector' => $containerSelector,
                'fieldSelectors' => $processedFieldSelectors,
            ];

            if ($linkFieldName && $linkSelector) {
                $payload['linkFieldName'] = $linkFieldName; // Name usually doesn't need placeholder replacement
                $payload['linkSelector'] = $linkSelector;
            }
            
            Log::info("ScraperNode: Sending request to scraper service {$scraperServiceUrl}", ['node_id' => $nodeId, 'payload' => $payload]);

            $response = Http::timeout(120)->post($scraperServiceUrl, $payload);

            if ($response->successful()) {
                $scrapedData = $response->json();
                $variables[$outputVariable] = $scrapedData;
                Log::info('ScraperNode: Successfully scraped data.', ['node_id' => $nodeId, 'output_variable' => $outputVariable, 'item_count' => is_array($scrapedData) ? count($scrapedData) : 'N/A']);
                return ['success' => true, 'output' => $scrapedData, 'output_variable' => $outputVariable];
            } else {
                $errorDetails = $response->json() ?: ['raw_body' => $response->body()];
                $errorMessage = 'ScraperNode: Failed to scrape data from service.';
                Log::error($errorMessage, ['node_id' => $nodeId, 'status_code' => $response->status(), 'response' => $errorDetails]);
                $variables[$outputVariable] = ['error' => $errorMessage, 'status' => $response->status(), 'details' => $errorDetails];
                return ['success' => false, 'error' => $errorMessage, 'details' => $errorDetails, 'output_variable' => $outputVariable];
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $errorMessage = 'ScraperNode: ConnectionException while calling scraper service.';
            Log::error($errorMessage, ['node_id' => $nodeId, 'error_message' => $e->getMessage()]);
            $variables[$outputVariable] = ['error' => $errorMessage, 'details' => $e->getMessage()];
            return ['success' => false, 'error' => $errorMessage, 'details' => $e->getMessage(), 'output_variable' => $outputVariable];
        }
        catch (\Exception $e) {
            $errorMessage = 'ScraperNode: General exception during execution.';
            Log::error($errorMessage, ['node_id' => $nodeId, 'error_message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $variables[$outputVariable] = ['error' => $errorMessage, 'details' => $e->getMessage()];
            return ['success' => false, 'error' => $errorMessage, 'details' => $e->getMessage(), 'output_variable' => $outputVariable];
        }
    }

    /**
     * Replaces placeholders like {{variable_name}} or {{variable.key.subkey}} in a string with values from the context.
     * Uses dot notation for nested array access.
     */
    protected function replacePlaceholders(string $text, array $context): string
    {
        if (strpos($text, '{{') === false) {
            return $text; // No placeholders, return as is
        }

        return preg_replace_callback('/\{\{([^{}]+)\}\}/', function ($matches) use ($context) {
            $variablePath = trim($matches[1]);
            $keys = explode('.', $variablePath);
            $value = $context;
            foreach ($keys as $key) {
                if (is_array($value) && isset($value[$key])) {
                    $value = $value[$key];
                } elseif (is_object($value) && isset($value->{$key})) {
                    $value = $value->{$key};
                }
                else {
                    return $matches[0]; // Path not found, return original placeholder
                }
            }
            // If the resolved value is an array or object, JSON encode it for safe string replacement
            return is_array($value) || is_object($value) ? json_encode($value) : (string)$value;
        }, $text);
    }

    // Transformation methods

    private function transformJsonParse($input)
    {
        if (!is_string($input)) {
            throw new \Exception("Input must be a string for JSON parsing");
        }

        return json_decode($input, true);
    }

    private function transformJsonStringify($input)
    {
        return json_encode($input);
    }

    private function transformToUppercase($input)
    {
        if (!is_string($input)) {
            throw new \Exception("Input must be a string for uppercase transformation");
        }

        return strtoupper($input);
    }

    private function transformToLowercase($input)
    {
        if (!is_string($input)) {
            throw new \Exception("Input must be a string for lowercase transformation");
        }

        return strtolower($input);
    }

    private function transformTrim($input)
    {
        if (!is_string($input)) {
            throw new \Exception("Input must be a string for trim transformation");
        }

        return trim($input);
    }

    private function transformExtractText($input, $regex)
    {
        if (!is_string($input)) {
            throw new \Exception("Input must be a string for text extraction");
        }

        if (!$regex) {
            throw new \Exception("Regex pattern is required for text extraction");
        }

        preg_match($regex, $input, $matches);

        return $matches[1] ?? '';
    }

    private function transformCustomCode($input, $code, $variables)
    {
        if (!$code) {
            throw new \Exception("Custom code is required for custom transformation");
        }

        // Define a function with the custom code
        $function = function ($input) use ($code, $variables) {
            // Use eval cautiously - this should be replaced with a safer approach
            return eval($code);
        };

        // Execute the function with the input
        try {
            return $function($input);
        } catch (\Throwable $e) {
            throw new \Exception("Custom code execution error: " . $e->getMessage());
        }
    }
}

