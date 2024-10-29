<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    protected $taskRepository;
    protected $taskService;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $this->taskService = new TaskService($this->taskRepository);

        // Mock para garantir que o ID do usuário autenticado seja sempre 3
        Auth::shouldReceive('id')->andReturn(3);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_create_task()
    {
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'Pendente',
            'priority' => 'Média',
            'user_id' => 3, // ID do usuário mockado
        ];

        $this->taskRepository
            ->shouldReceive('create')
            ->once()
            ->with($taskData)
            ->andReturn(new Task($taskData));

        $task = $this->taskService->createTask($taskData);

        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals('Test Description', $task->description);
        $this->assertEquals('Pendente', $task->status);
        $this->assertEquals(3, $task->user_id);
    }

    
    public function test_can_start_task()
    {
        $task = new Task(['id' => 1, 'title' => 'Task to Start', 'status' => 'Pendente', 'user_id' => 3]);

        $this->taskRepository
            ->shouldReceive('updateStatus')
            ->once()
            ->with($task, 'In Progress')
            ->andReturn(new Task(['status' => 'In Progress']));

        $result = $this->taskService->startTask($task);
        $this->assertEquals('In Progress', $result->status);
    }

    public function test_can_update_task()
    {
        $task = new Task(['id' => 1, 'title' => 'Task to Update', 'user_id' => 3]);
        $updateData = ['title' => 'Updated Task Title'];

        $this->taskRepository
            ->shouldReceive('update')
            ->once()
            ->with($task, $updateData)
            ->andReturn(new Task(array_merge($task->toArray(), $updateData)));

        $updatedTask = $this->taskService->updateTask($task, $updateData);
        $this->assertEquals('Updated Task Title', $updatedTask->title);
    }

    public function test_can_complete_task()
    {
        $task = new Task(['id' => 1, 'title' => 'Task to Complete', 'status' => 'In Progress', 'user_id' => 3]);

        $this->taskRepository
            ->shouldReceive('updateStatus')
            ->once()
            ->with($task, 'Completed')
            ->andReturn(new Task(['status' => 'Completed']));

        $result = $this->taskService->completeTask($task);
        $this->assertEquals('Completed', $result->status);
    }
}