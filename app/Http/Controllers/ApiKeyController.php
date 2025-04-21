<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ApiKeyController extends Controller
{
    public function index()
    {
        $apiKeys = ApiKey::where('user_id', Auth::id())
            ->orderBy('provider')
            ->get();

        // Don't expose the full key, but provide a masked version
        $apiKeys->transform(function ($apiKey) {
            $apiKey->masked_key = $this->maskApiKey($apiKey->key);
            return $apiKey;
        });

        return Inertia::render('Pages/ApiKeys/Index', [
            'apiKeys' => $apiKeys
        ]);
    }

    protected function maskApiKey($key)
    {
        if (strlen($key) <= 8) {
            return '********';
        }

        return substr($key, 0, 4) . '...' . substr($key, -4);
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'key' => 'required|string',
            'label' => 'nullable|string|max:255',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset others for this provider
        if ($request->is_default) {
            ApiKey::where('user_id', Auth::id())
                ->where('provider', $request->provider)
                ->update(['is_default' => false]);
        }

        $apiKey = ApiKey::create([
            'user_id' => Auth::id(),
            'provider' => $request->provider,
            'key' => $request->key,
            'label' => $request->label,
            'is_default' => $request->is_default ?? false,
        ]);

        return redirect()->route('api-keys.index')
            ->with('success', 'API key added successfully.');
    }

    public function update(Request $request, ApiKey $apiKey)
    {
        if ($apiKey->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'label' => 'nullable|string|max:255',
            'is_default' => 'boolean',
        ]);

        // If new key is provided, validate and update it
        if ($request->filled('key')) {
            $request->validate([
                'key' => 'required|string',
            ]);

            $apiKey->key = $request->key;
        }

        // If this is set as default, unset others for this provider
        if ($request->is_default && !$apiKey->is_default) {
            ApiKey::where('user_id', Auth::id())
                ->where('provider', $apiKey->provider)
                ->update(['is_default' => false]);
        }

        $apiKey->label = $request->label;
        $apiKey->is_default = $request->is_default ?? $apiKey->is_default;
        $apiKey->save();

        return redirect()->route('api-keys.index')
            ->with('success', 'API key updated successfully.');
    }

    public function destroy(ApiKey $apiKey)
    {
        if ($apiKey->user_id !== Auth::id()) {
            abort(403);
        }

        $apiKey->delete();

        return redirect()->route('api-keys.index')
            ->with('success', 'API key deleted successfully.');
    }
}
