<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use App\Models\PromptVersion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PromptController extends Controller
{
    public function index()
    {
        $prompts = Prompt::with('currentVersion')
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

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

        $prompt->load(['versions' => function ($query) {
            $query->orderBy('version', 'desc');
        }]);

        return Inertia::render('Pages/Prompts/Show', [
            'prompt' => $prompt,
            'currentVersion' => $prompt->currentVersion(),
        ]);
    }

    public function edit(Prompt $prompt)
    {
        if ($prompt->user_id !== Auth::id()) {
            abort(403);
        }

        $prompt->load('currentVersion');

        return Inertia::render('Pages/Prompts/Edit', [
            'prompt' => $prompt,
            'currentVersion' => $prompt->currentVersion(),
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

        $currentVersion = $prompt->currentVersion();

        // Only create a new version if content has changed
        if ($currentVersion->content !== $request->content) {
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

        return redirect()->back()->with('success', 'Version set as current.');
    }
}
