<?php

// app/Jobs/ExecuteWorkflowJob.php
namespace App\Jobs;

use App\Models\ExecutionLog;
use App\Services\WorkflowExecutionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExecuteWorkflowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $executionLog;

    /**
     * Create a new job instance.
     */
    public function __construct(ExecutionLog $executionLog)
    {
        $this->executionLog = $executionLog;
    }

    /**
     * Execute the job.
     */
    public function handle(WorkflowExecutionService $workflowService): void
    {
        try {
            Log::info('Starting workflow execution', [
                'execution_id' => $this->executionLog->id,
                'workflow_id' => $this->executionLog->workflow_id
            ]);

            $result = $workflowService->execute($this->executionLog);

            Log::info('Workflow execution completed', [
                'execution_id' => $this->executionLog->id,
                'success' => $result['success']
            ]);

            // Emit event for real-time updates
            event(new \App\Events\WorkflowExecutionCompleted($this->executionLog));
        } catch (\Exception $e) {
            Log::error('Workflow execution failed', [
                'execution_id' => $this->executionLog->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update execution log with error
            $this->executionLog->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
                'completed_at' => now()
            ]);

            // Emit event for real-time updates
            event(new \App\Events\WorkflowExecutionFailed($this->executionLog));
        }
    }
}
