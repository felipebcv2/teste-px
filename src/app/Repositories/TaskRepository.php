<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllByUser(int $userId)
    {
        return Task::where('user_id', $userId)
                   ->select('id', 'title', 'status', 'deadline')
                   ->get();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function find(int $taskId): ?Task
    {
        return Task::find($taskId);
    }

    public function updateStatus(Task $task, string $status): Task
    {
        $task->status = $status;
        $task->save();

        return $task;
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task;
    }
}
