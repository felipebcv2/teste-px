<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function build()
    {
        return $this->subject('Task Notification')->view('emails.task_notification')->with([
            'taskTitle' => $this->task->title,
            'taskDescription' => $this->task->description,
            'taskStatus' => $this->task->status,
        ]);
    }
}
