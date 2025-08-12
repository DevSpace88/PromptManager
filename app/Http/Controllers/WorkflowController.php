<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use App\Models\Workflow;
use App\Models\ExecutionLog;
use App\Jobs\ExecuteWorkflowJob;
use App\Services\WorkflowExecutionService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Support\NamespacedCache;

class WorkflowController extends Controller
{
    protected $workflowService;

    public function __construct(WorkflowExecutionService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

//    public function index()
//    {
//        $workflows = Workflow::where('user_id', Auth::id())
//            ->orderBy('updated_at', 'desc')
//            ->get();
//
//        return Inertia::render('Pages/Workflows/Index', [
//            'workflows' => $workflows
//        ]);
//    }

    public function index()
    {
        $workflows = NamespacedCache::rememberUser(
            'workflows',
            (int) Auth::id(),
            'list_all',
            60,
            fn () => Workflow::where('user_id', Auth::id())
                ->orderBy('updated_at', 'desc')
                ->get()
        );

        $recentExecutions = NamespacedCache::rememberUser(
            'executions',
            (int) Auth::id(),
            'workflows_index_recent_5',
            60,
            fn () => ExecutionLog::with('workflow')
                ->where('user_id', Auth::id())
                ->latest()
                ->take(5)
                ->get()
        );

        return Inertia::render('Pages/Workflows/Index', [
            'workflows' => $workflows,
            'recentExecutions' => $recentExecutions
        ]);
    }

//    public function create()
//    {
//        return Inertia::render('Pages/Workflows/Create');
//    }

    public function create()
    {
        // Lade alle Prompts für diesen Benutzer
        $prompts = NamespacedCache::rememberUser(
            'prompts',
            (int) Auth::id(),
            'for_workflow_create',
            300,
            function () {
                return Prompt::with('currentVersion')
                    ->where('user_id', Auth::id())
                    ->get()
                    ->map(function ($prompt) {
                        return [
                            'id' => $prompt->id,
                            'name' => $prompt->title,
                            'content' => $prompt->currentVersion ? $prompt->currentVersion->content : ''
                        ];
                    });
            }
        );

        return Inertia::render('Pages/Workflows/Create', [
            'prompts' => $prompts
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'nodes' => 'required|array',
            'edges' => 'required|array',
            'settings' => 'nullable|array',
        ]);

        $workflow = Workflow::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'nodes' => $request->nodes,
            'edges' => $request->edges,
            'settings' => $request->settings,
            'is_active' => true,
        ]);

        return redirect()->route('workflows.show', $workflow)
            ->with('success', 'Workflow created successfully.');
    }

    public function show(Workflow $workflow)
    {
        if ($workflow->user_id !== Auth::id()) {
            abort(403);
        }

        $recentExecutions = NamespacedCache::rememberUser(
            'executions',
            (int) Auth::id(),
            "workflow_{$workflow->id}_recent_5",
            60,
            fn () => $workflow->executionLogs()
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        );

        return Inertia::render('Pages/Workflows/Show', [
            'workflow' => $workflow,
            'recentExecutions' => $recentExecutions,
        ]);
    }


    public function edit(Workflow $workflow)
    {
        if ($workflow->user_id !== Auth::id()) {
            abort(403);
        }

        // Lade alle Prompts für diesen Benutzer
        $prompts = Prompt::with('currentVersion')
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($prompt) {
                return [
                    'id' => $prompt->id,
                    'name' => $prompt->title,
                    'content' => $prompt->currentVersion ? $prompt->currentVersion->content : ''
                ];
            });

        return Inertia::render('Pages/Workflows/Edit', [
            'workflow' => $workflow,
            'prompts' => $prompts
        ]);
    }

    public function update(Request $request, Workflow $workflow)
    {
        if ($workflow->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'nodes' => 'required|array',
            'edges' => 'required|array',
            'settings' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $workflow->update([
            'name' => $request->name,
            'description' => $request->description,
            'nodes' => $request->nodes,
            'edges' => $request->edges,
            'settings' => $request->settings,
            'is_active' => $request->is_active ?? $workflow->is_active,
        ]);

        return redirect()->route('workflows.show', $workflow)
            ->with('success', 'Workflow updated successfully.');
    }

    public function destroy(Workflow $workflow)
    {
        if ($workflow->user_id !== Auth::id()) {
            abort(403);
        }

        $workflow->delete();

        return redirect()->route('workflows.index')
            ->with('success', 'Workflow deleted successfully.');
    }

    public function execute(Request $request, Workflow $workflow)
    {
        if ($workflow->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$workflow->is_active) {
            return response()->json(['error' => 'Workflow is not active'], 400);
        }

        $request->validate([
            'input_data' => 'nullable|array',
        ]);

        // Create execution log
        $executionLog = ExecutionLog::create([
            'workflow_id' => $workflow->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'input_data' => $request->input_data ?? [],
            'started_at' => now(),
        ]);

        // Dispatch job to run workflow
        ExecuteWorkflowJob::dispatch($executionLog);

        // Überprüfen, ob es ein AJAX-Request oder ein normaler Inertia-Request ist
        if ($request->wantsJson() || $request->header('X-Inertia') === 'false') {
            // Für AJAX/API-Anfragen: JSON-Antwort
            return response()->json([
                'execution_id' => $executionLog->id,
                'message' => 'Workflow execution started',
            ]);
        } else {
            // Für Inertia-Anfragen: Zurück zur Workflow-Ansicht mit Statusmeldung
            return redirect()->route('workflows.show', $workflow)
                ->with('success', 'Workflow execution started. Execution ID: ' . $executionLog->id);
        }
    }

    public function getExecutionStatus(ExecutionLog $executionLog)
    {
        if ($executionLog->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json([
            'status' => $executionLog->status,
            'started_at' => $executionLog->started_at,
            'completed_at' => $executionLog->completed_at,
            'node_results' => $executionLog->node_results,
            'output_data' => $executionLog->output_data,
            'error' => $executionLog->error,
        ]);
    }

//    public function exportWorkflow(Workflow $workflow)
//    {
//        if ($workflow->user_id !== Auth::id()) {
//            abort(403);
//        }
//
//        $exportData = [
//            'name' => $workflow->name,
//            'description' => $workflow->description,
//            'nodes' => $workflow->nodes,
//            'edges' => $workflow->edges,
//            'settings' => $workflow->settings,
//            'created_at' => $workflow->created_at,
//            'updated_at' => $workflow->updated_at,
//        ];
//
//        return response()->json($exportData);
//    }

//    public function exportWorkflow(Workflow $workflow)
//    {
//        if ($workflow->user_id !== Auth::id()) {
//            abort(403);
//        }
//
//        $exportData = [
//            'name' => $workflow->name,
//            'description' => $workflow->description,
//            'nodes' => $workflow->nodes,
//            'edges' => $workflow->edges,
//            'settings' => $workflow->settings,
//            'created_at' => $workflow->created_at,
//            'updated_at' => $workflow->updated_at,
//        ];
//
//        // JSON erstellen (mit formatierter Ausgabe für bessere Lesbarkeit)
//        $jsonData = json_encode($exportData, JSON_PRETTY_PRINT);
//
//        // Generiere einen sauberen Dateinamen basierend auf dem Workflow-Namen
//        $filename = Str::slug($workflow->name) . '-export.json';
//
//        // Zurückgeben als Download-Datei anstatt als JSON-Antwort
//        return response($jsonData)
//            ->header('Content-Type', 'application/json')
//            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
//    }

    public function exportWorkflow(Workflow $workflow)
    {
        if ($workflow->user_id !== Auth::id()) {
            abort(403);
        }

        $exportData = [
            'name' => $workflow->name,
            'description' => $workflow->description,
            'nodes' => $workflow->nodes,
            'edges' => $workflow->edges,
            'settings' => $workflow->settings,
            'created_at' => $workflow->created_at,
            'updated_at' => $workflow->updated_at,
        ];

        $jsonData = json_encode($exportData, JSON_PRETTY_PRINT);
        $filename = strtolower(str_replace(' ', '-', $workflow->name)) . '-export.json';

        return response()->streamDownload(function () use ($jsonData) {
            echo $jsonData;
        }, $filename, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // In WorkflowController.php:
    public function downloadWorkflow(Workflow $workflow)
    {
        if ($workflow->user_id !== Auth::id()) {
            abort(403);
        }

        $exportData = [
            'name' => $workflow->name,
            'description' => $workflow->description,
            'nodes' => $workflow->nodes,
            'edges' => $workflow->edges,
            'settings' => $workflow->settings,
            'created_at' => $workflow->created_at,
            'updated_at' => $workflow->updated_at,
        ];

        // JSON erstellen
        $jsonData = json_encode($exportData, JSON_PRETTY_PRINT);

        // Dateiname erstellen
        $filename = preg_replace('/[^a-z0-9]+/', '-', strtolower($workflow->name)) . '-export.json';

        return response()->streamDownload(function () use ($jsonData) {
            echo $jsonData;
        }, $filename, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function importWorkflow(Request $request)
    {
        $request->validate([
            'workflow_data' => 'required|json',
        ]);

        $workflowData = json_decode($request->workflow_data, true);

        if (!isset($workflowData['name']) || !isset($workflowData['nodes']) || !isset($workflowData['edges'])) {
            return response()->json(['error' => 'Invalid workflow data'], 400);
        }

        $workflow = Workflow::create([
            'user_id' => Auth::id(),
            'name' => $workflowData['name'],
            'description' => $workflowData['description'] ?? null,
            'nodes' => $workflowData['nodes'],
            'edges' => $workflowData['edges'],
            'settings' => $workflowData['settings'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('workflows.show', $workflow)
            ->with('success', 'Workflow imported successfully.');
    }
}
