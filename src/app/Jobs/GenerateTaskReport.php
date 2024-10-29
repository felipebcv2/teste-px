<?php

namespace App\Jobs;

use App\Mail\TaskReportMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class GenerateTaskReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $tasks = $this->user->tasks()->get();
        $csvData = $this->generateCSV($tasks);

        $filePath = "public/reports/{$this->user->id}_task_report.csv";
        Storage::put($filePath, $csvData);
    
        $downloadLink = url(Storage::url("reports/{$this->user->id}_task_report.csv"));

        Log::info("Relatório pronto e pode ser baixado no link: {$downloadLink}");

        // Mail::to($this->user->email)->send(new TaskReportMail($filePath));
    }

    private function generateCSV($tasks): string
    {
        $csv = "ID,Title,Description,Status,Priority,Deadline\n";
        foreach ($tasks as $task) {
            $csv .= "{$task->id},\"{$task->title}\",\"{$task->description}\","
                  . "{$task->status},{$task->priority},{$task->deadline}\n";
        }
        return $csv;
    }
}
