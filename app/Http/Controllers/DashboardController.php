<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\Prompt;
use App\Models\Workflow;
use App\Models\ExecutionLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Support\NamespacedCache;

class DashboardController extends Controller
{
    public function index()
    {
        // Authentifizierten Benutzer abrufen
        $user = auth()->user();

        // Zähle die Anzahl von Prompts, Workflows und API-Keys für den angemeldeten Benutzer (gecacht)
        $promptCount = NamespacedCache::rememberUser(
            'dashboard_counts',
            $user->id,
            'prompt_count',
            60,
            fn () => Prompt::where('user_id', $user->id)->count()
        );

        $workflowCount = NamespacedCache::rememberUser(
            'dashboard_counts',
            $user->id,
            'workflow_count',
            60,
            fn () => Workflow::where('user_id', $user->id)->count()
        );

        $apiKeyCount = NamespacedCache::rememberUser(
            'dashboard_counts',
            $user->id,
            'api_key_count',
            60,
            fn () => ApiKey::where('user_id', $user->id)->count()
        );

        // Hole die neuesten Ausführungslogs
        $recentExecutions = NamespacedCache::rememberUser(
            'executions',
            $user->id,
            'dashboard_recent_5',
            60,
            function () use ($user) {
                return ExecutionLog::with('workflow')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function ($log) {
                        return [
                            'id' => $log->id,
                            'workflow_name' => optional($log->workflow)->name,
                            'status' => $log->status,
                            'created_at' => $log->created_at,
                            'completed_at' => $log->completed_at,
                        ];
                    });
            }
        );

        // Hole die am häufigsten verwendeten Prompts (basierend auf Ausführungslogs)
        // Da keine direkte Beziehung zwischen Prompts und Logs besteht,
        // nehmen wir hier an, dass die neuesten Prompts relevant sind
        $popularPrompts = NamespacedCache::rememberUser(
            'prompts',
            $user->id,
            'popular_prompts_5',
            300,
            function () use ($user) {
                return Prompt::where('user_id', $user->id)
                    ->with('versions')
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function ($prompt) {
                        return [
                            'id' => $prompt->id,
                            'title' => $prompt->title,
                            'description' => $prompt->description,
                            'created_at' => $prompt->created_at,
                            'version_count' => $prompt->versions->count()
                        ];
                    });
            }
        );

        return Inertia::render('Pages/Dashboard', [
            'promptCount' => $promptCount,
            'workflowCount' => $workflowCount,
            'apiKeyCount' => $apiKeyCount,
            'recentExecutions' => $recentExecutions,
            'popularPrompts' => $popularPrompts,
        ]);
    }

}
