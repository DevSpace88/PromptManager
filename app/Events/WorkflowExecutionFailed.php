<?php

// app/Events/WorkflowExecutionFailed.php
namespace App\Events;

use App\Models\ExecutionLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkflowExecutionFailed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $executionLog;

    /**
     * Create a new event instance.
     */
    public function __construct(ExecutionLog $executionLog)
    {
        $this->executionLog = $executionLog;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('workflow-execution.' . $this->executionLog->id),
            new PrivateChannel('user.' . $this->executionLog->user_id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'execution_id' => $this->executionLog->id,
            'workflow_id' => $this->executionLog->workflow_id,
            'status' => 'failed',
            'error' => $this->executionLog->error,
            'completed_at' => $this->executionLog->completed_at,
        ];
    }
}
