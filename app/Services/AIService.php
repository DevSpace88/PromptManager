<?php

namespace App\Services;

use App\Models\ApiKey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Generate a completion from an AI provider
     */
    public function generateCompletion(string $provider, string $model, string $prompt, float $temperature = 0.7, int $maxTokens = 2000)
    {
        // Get API key for the provider
        $apiKey = $this->getApiKey($provider);

        if (!$apiKey) {
            return [
                'error' => true,
                'message' => "No API key found for provider: {$provider}",
            ];
        }

        // Call the appropriate provider method
        return match($provider) {
            'openai' => $this->callOpenAi($apiKey, $model, $prompt, $temperature, $maxTokens),
            'anthropic' => $this->callAnthropic($apiKey, $model, $prompt, $temperature, $maxTokens),
            'google' => $this->callGoogle($apiKey, $model, $prompt, $temperature, $maxTokens),
            'ollama' => $this->callOllama($apiKey, $model, $prompt, $temperature, $maxTokens),
            'deepseek' => $this->callDeepSeek($apiKey, $model, $prompt, $temperature, $maxTokens),
            default => [
                'error' => true,
                'message' => "Unsupported provider: {$provider}",
            ],
        };
    }

    /**
     * Get API key for a provider
     */
    protected function getApiKey(string $provider)
    {
        // First try to get the default key for this provider
        $apiKey = ApiKey::where('user_id', Auth::id())
            ->where('provider', $provider)
            ->where('is_default', true)
            ->first();

        // If no default key, get any key for this provider
        if (!$apiKey) {
            $apiKey = ApiKey::where('user_id', Auth::id())
                ->where('provider', $provider)
                ->first();
        }

        return $apiKey ? $apiKey->key : null;
    }

    /**
     * Call OpenAI API
     */
    protected function callOpenAi(string $apiKey, string $model, string $prompt, float $temperature, int $maxTokens)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'error' => false,
                    'text' => $data['choices'][0]['message']['content'],
                    'model' => $model,
                    'provider' => 'openai',
                    'raw_response' => $data,
                ];
            } else {
                Log::error('OpenAI API error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return [
                    'error' => true,
                    'message' => 'OpenAI API error: ' . ($response->json()['error']['message'] ?? 'Unknown error'),
                    'status' => $response->status(),
                ];
            }
        } catch (\Exception $e) {
            Log::error('OpenAI API exception', [
                'exception' => $e->getMessage(),
            ]);

            return [
                'error' => true,
                'message' => 'OpenAI API exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Call Anthropic API
     */
    protected function callAnthropic(string $apiKey, string $model, string $prompt, float $temperature, int $maxTokens)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json',
                'anthropic-version' => '2023-06-01',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'error' => false,
                    'text' => $data['content'][0]['text'],
                    'model' => $model,
                    'provider' => 'anthropic',
                    'raw_response' => $data,
                ];
            } else {
                Log::error('Anthropic API error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return [
                    'error' => true,
                    'message' => 'Anthropic API error: ' . ($response->json()['error']['message'] ?? 'Unknown error'),
                    'status' => $response->status(),
                ];
            }
        } catch (\Exception $e) {
            Log::error('Anthropic API exception', [
                'exception' => $e->getMessage(),
            ]);

            return [
                'error' => true,
                'message' => 'Anthropic API exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Call Google AI API (Gemini)
     */
    protected function callGoogle(string $apiKey, string $model, string $prompt, float $temperature, int $maxTokens)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generation_config' => [
                    'temperature' => $temperature,
                    'maxOutputTokens' => $maxTokens,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'error' => false,
                    'text' => $data['candidates'][0]['content']['parts'][0]['text'],
                    'model' => $model,
                    'provider' => 'google',
                    'raw_response' => $data,
                ];
            } else {
                Log::error('Google AI API error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return [
                    'error' => true,
                    'message' => 'Google AI API error: ' . ($response->json()['error']['message'] ?? 'Unknown error'),
                    'status' => $response->status(),
                ];
            }
        } catch (\Exception $e) {
            Log::error('Google AI API exception', [
                'exception' => $e->getMessage(),
            ]);

            return [
                'error' => true,
                'message' => 'Google AI API exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Call DeepSeek API
     */
    protected function callDeepSeek(string $apiKey, string $model, string $prompt, float $temperature, int $maxTokens)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'error' => false,
                    'text' => $data['choices'][0]['message']['content'],
                    'model' => $model,
                    'provider' => 'deepseek',
                    'raw_response' => $data,
                ];
            } else {
                Log::error('DeepSeek API error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return [
                    'error' => true,
                    'message' => 'DeepSeek API error: ' . ($response->json()['error']['message'] ?? 'Unknown error'),
                    'status' => $response->status(),
                ];
            }
        } catch (\Exception $e) {
            Log::error('DeepSeek API exception', [
                'exception' => $e->getMessage(),
            ]);

            return [
                'error' => true,
                'message' => 'DeepSeek API exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Call Ollama API (local LLMs)
     */
    protected function callOllama(string $apiKey, string $model, string $prompt, float $temperature, int $maxTokens)
    {
        try {
            // For Ollama, the API key might actually be the base URL
            $baseUrl = $apiKey;
            if (!str_starts_with($baseUrl, 'http')) {
                $baseUrl = 'http://' . $baseUrl;
            }

            $response = Http::post("{$baseUrl}/api/generate", [
                'model' => $model,
                'prompt' => $prompt,
                'temperature' => $temperature,
                'num_predict' => $maxTokens,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'error' => false,
                    'text' => $data['response'],
                    'model' => $model,
                    'provider' => 'ollama',
                    'raw_response' => $data,
                ];
            } else {
                Log::error('Ollama API error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return [
                    'error' => true,
                    'message' => 'Ollama API error: ' . ($response->json()['error'] ?? 'Unknown error'),
                    'status' => $response->status(),
                ];
            }
        } catch (\Exception $e) {
            Log::error('Ollama API exception', [
                'exception' => $e->getMessage(),
            ]);

            return [
                'error' => true,
                'message' => 'Ollama API exception: ' . $e->getMessage(),
            ];
        }
    }
}

