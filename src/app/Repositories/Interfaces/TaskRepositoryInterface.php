<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function getAllByUser(int $userId);
    public function find(int $taskId): ?Task;
    public function updateStatus(Task $task, string $status): Task;
    public function update(Task $task, array $data): Task;
}
