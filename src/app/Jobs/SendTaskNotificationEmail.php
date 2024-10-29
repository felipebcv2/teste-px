<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use App\Mail\TaskNotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Log;

class SendTaskNotificationEmail implements ShouldQueue
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
        Log::info('Detalhes do email:', [
            'title' => $this->task->title,
            'description' => $this->task->description,
            'status' => $this->task->status,
            'user_email' => $this->user->email,
        ]);

        //Mail::to($this->user->email)->send(new TaskNotificationMail($this->task));
    }
}
