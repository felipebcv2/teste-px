<?php

namespace App\Services;

use App\Jobs\SendTaskCompletedNotification;
use App\Jobs\SendTaskCreatedNotification;
use App\Jobs\SendTaskUpdatedNotification;
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
        $task = $this->taskRepository->create($data);

        SendTaskCreatedNotification::dispatch($task, Auth::user());

        return $task;
    }

    public function getAllTasks(User $user)
    {
        return $this->taskRepository->getAllByUser($user->id);
    }

    public function getTask(int $task_id): ?Task
    {
        return $this->taskRepository->find($task_id);
    }

    public function startTask(Task $task)
    {
        return $this->taskRepository->updateStatus($task, 'In Progress');
    }

    public function updateTask(Task $task, array $data)
    {
        $task = $this->taskRepository->update($task, $data);
        SendTaskUpdatedNotification::dispatch($task, Auth::user());
        return $task;
    }
    public function completeTask(Task $task)
    {
        $task = $this->taskRepository->updateStatus($task, 'Completed');
        SendTaskCompletedNotification::dispatch($task, Auth::user());

        return $task;
    }
}
