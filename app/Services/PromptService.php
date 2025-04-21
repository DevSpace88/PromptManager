<?php


namespace App\Services;

use App\Models\Prompt;
use App\Models\PromptVersion;
use Illuminate\Support\Facades\DB;

class PromptService
{
    /**
     * Create a new prompt with its first version
     */
    public function createPrompt(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            // Create the prompt
            $prompt = Prompt::create([
                'user_id' => $userId,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'tags' => $data['tags'] ?? [],
            ]);

            // Create the first version
            $promptVersion = new PromptVersion();
            $promptVersion->prompt_id = $prompt->id;
            $promptVersion->content = $data['content'];
            $promptVersion->version = 1;
            $promptVersion->is_current = true;
            $promptVersion->extractVariablesFromContent();
            $promptVersion->save();

            return $prompt;
        });
    }

    /**
     * Update a prompt and create a new version if content changed
     */
    public function updatePrompt(Prompt $prompt, array $data)
    {
        return DB::transaction(function () use ($prompt, $data) {
            // Update the prompt
            $prompt->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? $prompt->description,
                'tags' => $data['tags'] ?? $prompt->tags,
            ]);

            // Get current version
            $currentVersion = $prompt->currentVersion();

            // Only create a new version if content has changed
            if (isset($data['content']) && $currentVersion && $currentVersion->content !== $data['content']) {
                // Mark old version as not current
                $currentVersion->update(['is_current' => false]);

                // Create new version
                $promptVersion = new PromptVersion();
                $promptVersion->prompt_id = $prompt->id;
                $promptVersion->content = $data['content'];
                $promptVersion->version = $currentVersion->version + 1;
                $promptVersion->is_current = true;
                $promptVersion->extractVariablesFromContent();
                $promptVersion->save();
            }

            return $prompt;
        });
    }

    /**
     * Set a specific version as the current one
     */
    public function setCurrentVersion(Prompt $prompt, PromptVersion $version)
    {
        return DB::transaction(function () use ($prompt, $version) {
            // Ensure the version belongs to this prompt
            if ($version->prompt_id !== $prompt->id) {
                throw new \Exception('Version does not belong to this prompt');
            }

            // Update all versions to not be current
            $prompt->versions()->update(['is_current' => false]);

            // Set this version as current
            $version->update(['is_current' => true]);

            return $prompt;
        });
    }

    /**
     * Generate a prompt template from a description using AI
     */
    public function generatePromptTemplate(string $description, string $provider = 'openai')
    {
        $aiService = app(AiService::class);

        $systemPrompt = "You are an expert at writing effective AI prompts. Create a prompt template based on the following description. Include variables in {{variable_name}} format where appropriate.";

        $response = $aiService->generateCompletion(
            $provider,
            'gpt-4', // Using a capable model for prompt generation
            $systemPrompt . "\n\nDescription: " . $description,
            0.7,
            2000
        );

        if ($response['error']) {
            throw new \Exception("Failed to generate prompt template: " . $response['message']);
        }

        return $response['text'];
    }

    /**
     * Optimize a prompt using AI suggestions
     */
    public function optimizePrompt(string $content, string $provider = 'openai')
    {
        $aiService = app(AiService::class);

        $systemPrompt = "You are an expert at optimizing AI prompts. Analyze the following prompt and suggest improvements to make it more effective. Keep variables in {{variable_name}} format.";

        $response = $aiService->generateCompletion(
            $provider,
            'gpt-4', // Using a capable model for prompt optimization
            $systemPrompt . "\n\nPrompt to optimize: " . $content,
            0.7,
            2000
        );

        if ($response['error']) {
            throw new \Exception("Failed to optimize prompt: " . $response['message']);
        }

        return $response['text'];
    }
}
