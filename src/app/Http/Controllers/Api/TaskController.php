<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use App\Services\Interfaces\TaskServiceInterface;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(TaskStoreRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());

        return response()->json([
            'message' => 'Task created successfully.',
            'task' => $task,
        ], 201);
    }

    public function index()
    {
        $tasks = $this->taskService->getAllTasks(Auth::user());
        return response()->json($tasks);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return response()->json($this->taskService->getTask($task));
    }

    public function start(Task $task)
    {
        $this->authorize('update', $task);
        $task = $this->taskService->startTask($task);

        return response()->json([
            'message' => 'Task started successfully.',
            'task' => $task,
        ]);
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task = $this->taskService->updateTask($task, $request->validated());

        return response()->json([
            'message' => 'Task updated successfully.',
            'task' => $task,
        ]);
    }

    public function complete(Task $task)
    {
        $this->authorize('update', $task);
        $task = $this->taskService->completeTask($task);

        return response()->json([
            'message' => 'Task completed successfully.',
            'task' => $task,
        ]);
    }
}
