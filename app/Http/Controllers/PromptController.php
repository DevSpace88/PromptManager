<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use App\Models\PromptVersion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Support\NamespacedCache;

class PromptController extends Controller
{
    public function index()
    {
        $prompts = NamespacedCache::rememberUser(
            'prompts',
            (int) Auth::id(),
            'list_all',
            60,
            fn () => Prompt::with('currentVersion')
                ->where('user_id', Auth::id())
                ->orderBy('updated_at', 'desc')
                ->get()
        );

        return Inertia::render('Pages/Prompts/Index', [
            'prompts' => $prompts
        ]);
    }

    public function create()
    {
        return Inertia::render('Pages/Prompts/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'tags' => 'nullable|array',
        ]);

        $prompt = Prompt::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'tags' => $request->tags,
        ]);

        $promptVersion = new PromptVersion();
        $promptVersion->prompt_id = $prompt->id;
        $promptVersion->content = $request->content;
        $promptVersion->version = 1;
        $promptVersion->is_current = true;
        $promptVersion->extractVariablesFromContent();
        $promptVersion->save();

        return redirect()->route('prompts.show', $prompt)
            ->with('success', 'Prompt created successfully.');
    }

    public function show(Prompt $prompt)
    {
        if ($prompt->user_id !== Auth::id()) {
            abort(403);
        }

        $prompt->load([
            'versions' => function ($query) {
                $query->orderBy('version', 'desc');
            },
            'currentVersion'
        ]);

        return Inertia::render('Pages/Prompts/Show', [
            'prompt' => $prompt,
            'currentVersion' => $prompt->currentVersion, // Direkt auf die geladene Beziehung zugreifen
        ]);
    }

    public function edit(Prompt $prompt)
    {
        if ($prompt->user_id !== Auth::id()) {
            abort(403);
        }

        $prompt->load('currentVersion')->first();

        return Inertia::render('Pages/Prompts/Edit', [
            'prompt' => $prompt,
            'currentVersion' => $prompt->currentVersion,
        ]);
    }

    public function update(Request $request, Prompt $prompt)
    {
        if ($prompt->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'tags' => 'nullable|array',
        ]);

        $prompt->update([
            'title' => $request->title,
            'description' => $request->description,
            'tags' => $request->tags,
        ]);

        $currentVersion = $prompt->currentVersion()->first();

        // Only create a new version if content has changed
        if ($currentVersion && $currentVersion->content !== $request->content) {

            // Mark old version as not current
            $currentVersion->update(['is_current' => false]);

            // Create new version
            $promptVersion = new PromptVersion();
            $promptVersion->prompt_id = $prompt->id;
            $promptVersion->content = $request->content;
            $promptVersion->version = $currentVersion->version + 1;
            $promptVersion->is_current = true;
            $promptVersion->extractVariablesFromContent();
            $promptVersion->save();
        }

        return redirect()->route('prompts.show', $prompt)
            ->with('success', 'Prompt updated successfully.');
    }

    public function destroy(Prompt $prompt)
    {
        if ($prompt->user_id !== Auth::id()) {
            abort(403);
        }

        $prompt->delete();

        return redirect()->route('prompts.index')
            ->with('success', 'Prompt deleted successfully.');
    }


    public function getVersions(Prompt $prompt)
    {
        if ($prompt->user_id !== Auth::id()) {
            abort(403);
        }

        $versions = $prompt->versions()
            ->orderBy('version', 'desc')
            ->get()
            ->map(function ($version) {
                return [
                    'id' => $version->id,
                    'version' => $version->version,
                    'created_at' => $version->created_at,
                    'is_current' => $version->is_current,
                ];
            });

        return response()->json(['versions' => $versions]);
    }

    public function setVersion(Request $request, Prompt $prompt, PromptVersion $version)
    {
        if ($prompt->user_id !== Auth::id()) {
            abort(403);
        }

        // Ensure the version belongs to this prompt
        if ($version->prompt_id !== $prompt->id) {
            abort(400, 'Invalid version');
        }

        // Update all versions to not be current
        $prompt->versions()->update(['is_current' => false]);

        // Set this version as current
        $version->update(['is_current' => true]);

        // Touch the prompt to update its updated_at timestamp
        $prompt->touch();

        // Redirect zurück mit Erfolgsmeldung
        return redirect()->back()->with('success', 'Version set as current.');
//        return redirect()->route('prompts.show', $prompt);

    }

    public function previewVersion(Prompt $prompt, PromptVersion $version)
    {
        if ($prompt->user_id !== Auth::id()) {
            abort(403);
        }

        // Ensure the version belongs to this prompt
        if ($version->prompt_id !== $prompt->id) {
            abort(400, 'Invalid version');
        }

        return response()->json([
            'id' => $version->id,
            'content' => $version->content,
            'variables' => $version->variables,
            'version' => $version->version,
            'created_at' => $version->created_at,
            'is_current' => $version->is_current,
        ]);
    }

}
