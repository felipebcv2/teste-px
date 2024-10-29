<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     title="Documentação da API de Tarefas",
 *     version="1.0.0",
 *     description="API para gerenciar tarefas, incluindo criação, atualização e conclusão de tarefas.",
 *     @OA\Contact(
 *         email="felipebcv@gmail.com",
 *         name="Felipe Vieira Andrade"
 *     )
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 *  * @OA\Components(
 *     @OA\Schema(
 *         schema="Task",
 *         type="object",
 *         title="Task",
 *         properties={
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Título da Tarefa Exemplo"),
 *             @OA\Property(property="description", type="string", example="Descrição da tarefa exemplo"),
 *             @OA\Property(property="status", type="string", example="Pendente"),
 *             @OA\Property(property="priority", type="string", example="Média"),
 *             @OA\Property(property="deadline", type="string", format="date", example="2024-12-31")
 *         }
 *     ),
 *     @OA\Schema(
 *         schema="TaskStoreRequest",
 *         type="object",
 *         title="Requisição para Criar Tarefa",
 *         required={"title", "description", "status", "priority"},
 *         properties={
 *             @OA\Property(property="title", type="string", example="Título da Nova Tarefa"),
 *             @OA\Property(property="description", type="string", example="Descrição detalhada da tarefa"),
 *             @OA\Property(property="status", type="string", example="Pendente"),
 *             @OA\Property(property="priority", type="string", example="Média"),
 *             @OA\Property(property="deadline", type="string", format="date", example="2024-12-31")
 *         }
 *     ),
 *     @OA\Schema(
 *         schema="TaskUpdateRequest",
 *         type="object",
 *         title="Requisição para Atualizar Tarefa",
 *         properties={
 *             @OA\Property(property="title", type="string", example="Título Atualizado da Tarefa"),
 *             @OA\Property(property="description", type="string", example="Descrição atualizada"),
 *             @OA\Property(property="status", type="string", example="Em Andamento"),
 *             @OA\Property(property="priority", type="string", example="Alta"),
 *             @OA\Property(property="deadline", type="string", format="date", example="2024-12-31")
 *         }
 *     )
 * )

 */

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

    /**
     * @OA\Post(
     *     path="/api/v1/tasks",
     *     summary="Create a new task",
     *     description="Creates a new task for the authenticated user.",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully.",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function store(TaskStoreRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());

        return response()->json([
            'message' => 'Task created successfully.',
            'task' => $task,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks",
     *     summary="Get all tasks",
     *     description="Retrieves all tasks for the authenticated user.",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Task"))
     *     )
     * )
     */
    public function index()
    {
        $tasks = $this->taskService->getAllTasks(Auth::user());
        return response()->json($tasks);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks/{id}",
     *     summary="Get task by ID",
     *     description="Fetches a specific task by its ID.",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task details",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function show(int $task_id)
    {
        $task = $this->taskService->getTask($task_id);

        if (!$task) {
            return response()->json(['message' => 'Task not found.'], 404);
        }

    $this->authorize('view', $task);

    return response()->json($task);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tasks/{id}/start",
     *     summary="Start a task",
     *     description="Starts the task, setting its status to 'In Progress'.",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task started successfully.",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function start(Task $task)
    {
        $this->authorize('update', $task);
        $task = $this->taskService->startTask($task);

        return response()->json([
            'message' => 'Task started successfully.',
            'task' => $task,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tasks/{id}",
     *     summary="Update a task",
     *     description="Updates the details of a specific task.",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully.",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task = $this->taskService->updateTask($task, $request->validated());

        return response()->json([
            'message' => 'Task updated successfully.',
            'task' => $task,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tasks/{id}/complete",
     *     summary="Complete a task",
     *     description="Marks the task as 'Completed'.",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task completed successfully.",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
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
