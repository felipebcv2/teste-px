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

        Auth::shouldReceive('id')->andReturn(1);
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
            'user_id' => 1,
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
        $this->assertEquals(1, $task->user_id);
    }
}
