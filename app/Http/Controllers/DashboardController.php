<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\Prompt;
use App\Models\Workflow;
use App\Models\ExecutionLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Authentifizierten Benutzer abrufen
        $user = auth()->user();

        // Zähle die Anzahl von Prompts, Workflows und API-Keys für den angemeldeten Benutzer
        $promptCount = Prompt::where('user_id', $user->id)->count();
        $workflowCount = Workflow::where('user_id', $user->id)->count();
        $apiKeyCount = ApiKey::where('user_id', $user->id)->count();

        // Hole die neuesten Ausführungslogs
        $recentExecutions = ExecutionLog::with('workflow')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'workflow_name' => $log->workflow->name,
                    'status' => $log->status,
                    'created_at' => $log->created_at,
                    'completed_at' => $log->completed_at,
                ];
            });

        // Hole die am häufigsten verwendeten Prompts (basierend auf Ausführungslogs)
        // Da keine direkte Beziehung zwischen Prompts und Logs besteht,
        // nehmen wir hier an, dass die neuesten Prompts relevant sind
        $popularPrompts = Prompt::where('user_id', $user->id)
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

        return Inertia::render('Pages/Dashboard', [
            'promptCount' => $promptCount,
            'workflowCount' => $workflowCount,
            'apiKeyCount' => $apiKeyCount,
            'recentExecutions' => $recentExecutions,
            'popularPrompts' => $popularPrompts,
        ]);
    }

}
