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
            return $nodeResults[$nodeId];
        }

        // Process the node based on its type
        $result = match($node['type']) {
            'prompt' => $this->executePromptNode($node, $variables),
            'condition' => $this->executeConditionNode($node, $variables, $workflow, $nodeResults),
            'input' => $this->executeInputNode($node, $variables),
            'output' => $this->executeOutputNode($node, $variables),
            'api' => $this->executeApiNode($node, $variables),
            'transform' => $this->executeTransformNode($node, $variables),
            default => throw new \Exception("Unsupported node type: {$node['type']}"),
        };

        // Store the result
        $nodeResults[$nodeId] = $result;

        // Process next nodes (if not a condition node, which handles its own flow)
        if ($node['type'] !== 'condition') {
            $nextNodes = $this->getNextNodes($nodeId, $workflow);
            foreach ($nextNodes as $nextNodeId) {
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
        $inputVariable = $data['input_variable'] ?? '';
        $outputVariable = $data['output_variable'] ?? '';
        $transformation = $data['transformation'] ?? '';

        if (!isset($variables[$inputVariable])) {
            throw new \Exception("Input variable not found: {$inputVariable}");
        }

        $input = $variables[$inputVariable];

        // Apply transformation
        $output = match($transformation) {
            'json_parse' => $this->transformJsonParse($input),
            'json_stringify' => $this->transformJsonStringify($input),
            'to_uppercase' => $this->transformToUppercase($input),
            'to_lowercase' => $this->transformToLowercase($input),
            'trim' => $this->transformTrim($input),
            'extract_text' => $this->transformExtractText($input, $data['regex'] ?? null),
            'custom_code' => $this->transformCustomCode($input, $data['code'] ?? null),
            default => throw new \Exception("Unsupported transformation: {$transformation}"),
        };

        // Store result in variables
        $variables[$outputVariable] = $output;

        return [
            'success' => true,
            'input_variable' => $inputVariable,
            'output_variable' => $outputVariable,
            'transformation' => $transformation,
        ];
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

    private function transformCustomCode($input, $code)
    {
        if (!$code) {
            throw new \Exception("Custom code is required for custom transformation");
        }

        // Define a function with the custom code
        $function = function ($input) use ($code) {
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

