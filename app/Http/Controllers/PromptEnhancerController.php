<?php

namespace App\Http\Controllers;

use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromptEnhancerController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Generate an improved prompt using AI
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enhance(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'context' => 'nullable|string',
        ]);

        // Prepare the system prompt for the AI
        $systemPrompt = "You are an expert prompt engineer. Your task is to improve the given prompt to make it more effective for AI use. Follow these guidelines:
        - Make the prompt more specific and detailed
        - Use clear instructions
        - Add structure to the expected output where appropriate
        - Incorporate best practices for prompt engineering
        - Maintain the original intent of the prompt
        - Keep the enhanced prompt concise yet comprehensive";

        // Create the user message
        $userMessage = "Please improve this prompt: {$request->prompt}";

        // If context is provided, add it
        if ($request->has('context')) {
            $userMessage .= "\n\nAdditional context: {$request->context}";
        }

        // Call AI Service with retry logic
        $maxAttempts = 3;
        $attempt = 0;
        $success = false;
        $aiResponse = null;

        while ($attempt < $maxAttempts && !$success) {
            try {
                // Use the AI service to generate an improved prompt
                // This assumes your AiService has a method for chat completions
                $aiResponse = $this->aiService->generateCompletion(
                    'openai',  // or your default provider
                    'gpt-4',   // or appropriate model
                    $systemPrompt,
                    $userMessage,
                    0.7,       // moderate temperature for creativity
                    800        // max tokens for response
                );

                $success = true;
            } catch (\Exception $e) {
                $attempt++;
                if ($attempt >= $maxAttempts) {
                    return response()->json([
                        'error' => 'Failed to generate improved prompt after multiple attempts',
                        'details' => $e->getMessage()
                    ], 500);
                }
            }
        }

        // Return the improved prompt
        return response()->json([
            'original_prompt' => $request->prompt,
            'improved_prompt' => $aiResponse['content'] ?? $aiResponse ?? $request->prompt,
        ]);
    }
}
