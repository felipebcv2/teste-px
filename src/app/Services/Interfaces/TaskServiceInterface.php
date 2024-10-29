<?php

namespace App\Services\Interfaces;

use App\Models\Task;
use App\Models\User;

interface TaskServiceInterface
{

    public function createTask(array $data);

    public function getAllTasks(User $user);

    public function getTask(int $taskId);

    public function startTask(Task $task);

    public function updateTask(Task $task, array $data);

    public function completeTask(Task $task);

}
