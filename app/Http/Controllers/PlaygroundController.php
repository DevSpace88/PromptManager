<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use App\Services\AiService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PlaygroundController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $prompts = Prompt::with('currentVersion')
            ->where('user_id', Auth::id())
            ->orderBy('title')
            ->get();

        return Inertia::render('Pages/Playground/Index', [
            'prompts' => $prompts,
        ]);
    }

    public function show(Prompt $prompt)
    {
        if ($prompt->user_id !== Auth::id()) {
            abort(403);
        }

        $prompt->load('currentVersion');

        return Inertia::render('Pages/Playground/Show', [
            'prompt' => $prompt,
            'currentVersion' => $prompt->currentVersion(),
            'versions' => $prompt->versions,
        ]);
    }

    public function test(Request $request)
    {
        $request->validate([
            'prompt_id' => 'nullable|exists:prompts,id',
            'content' => 'required|string',
            'variables' => 'nullable|array',
            'provider' => 'required|string',
            'model' => 'required|string',
            'temperature' => 'required|numeric|min:0|max:2',
            'max_tokens' => 'required|integer|min:1|max:8192',
        ]);

        // Replace variables in content
        $content = $request->content;
        if ($request->variables) {
            foreach ($request->variables as $key => $value) {
                $content = str_replace("{{" . $key . "}}", $value, $content);
            }
        }

        // Call AI Service
        $response = $this->aiService->generateCompletion(
            $request->provider,
            $request->model,
            $content,
            $request->temperature,
            $request->max_tokens
        );

        return response()->json($response);
    }

    public function compare(Request $request)
    {
        $request->validate([
            'prompt_id' => 'required|exists:prompts,id',
            'version_ids' => 'required|array',
            'version_ids.*' => 'exists:prompt_versions,id',
            'variables' => 'required|array',
            'provider' => 'required|string',
            'model' => 'required|string',
            'temperature' => 'required|numeric|min:0|max:2',
            'max_tokens' => 'required|integer|min:1|max:8192',
        ]);

        $prompt = Prompt::findOrFail($request->prompt_id);

        if ($prompt->user_id !== Auth::id()) {
            abort(403);
        }

        $results = [];

        foreach ($request->version_ids as $versionId) {
            $version = $prompt->versions()->findOrFail($versionId);

            // Replace variables in content
            $content = $version->content;
            foreach ($request->variables as $key => $value) {
                $content = str_replace("{{" . $key . "}}", $value, $content);
            }

            // Call AI Service
            $response = $this->aiService->generateCompletion(
                $request->provider,
                $request->model,
                $content,
                $request->temperature,
                $request->max_tokens
            );

            $results[] = [
                'version_id' => $versionId,
                'version_number' => $version->version,
                'response' => $response,
            ];
        }

        return response()->json(['results' => $results]);
    }
}
