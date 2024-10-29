<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Services\Interfaces\TaskServiceInterface;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Auth;

class TaskService implements TaskServiceInterface
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function createTask(array $data): Task
    {
        $data['user_id'] = Auth::id();
        return $this->taskRepository->create($data);
    }

    public function getAllTasks(User $user)
    {
        return $this->taskRepository->getAllByUser($user->id);
    }

    public function getTask(Task $task)
    {
        return $this->taskRepository->find($task->id);
    }

    public function startTask(Task $task)
    {
        return $this->taskRepository->updateStatus($task, 'In Progress');
    }

    public function updateTask(Task $task, array $data)
    {
        return $this->taskRepository->update($task, $data);
    }

    public function completeTask(Task $task)
    {
        return $this->taskRepository->updateStatus($task, 'Completed');
    }
}
