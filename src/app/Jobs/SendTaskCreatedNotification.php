<?php

namespace App\Jobs;

use App\Mail\TaskCreatedMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class SendTaskCreatedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;
    protected $user;

    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
    }

    public function handle()
    {
        Log::info("Nova tarefa criada: '{$this->task->title}'", [
            'título' => $this->task->title,
            'descrição' => $this->task->description,
            'status' => $this->task->status,
            'prioridade' => $this->task->priority,
            'prazo' => $this->task->deadline,
            'usuario' => $this->user->email,
        ]);

        // Mail::to($this->user->email)->send(new TaskCreatedMail($this->task));
    }
}
