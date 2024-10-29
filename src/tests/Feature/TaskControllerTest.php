<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_can_create_task()
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'Task Description',
            'status' => 'Pendente',
            'priority' => 'Média',
            'deadline' => now()->addDays(3)->toDateString(),
        ];

        $response = $this->postJson('/api/v1/tasks', $taskData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'task' => [
                         'id', 'title', 'description', 'status', 'priority', 'deadline',
                     ]
                 ]);
    }

    public function test_can_get_all_tasks()
    {
        Task::factory()->count(3)->for($this->user)->create();

        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200)
                 ->assertJsonStructure([[
                     'id', 'title', 'status', 'deadline'
                 ]]);
    }

    public function test_can_view_specific_task()
    {
        $task = Task::factory()->for($this->user)->create(['deadline' => now()->addDays(3)->toDateString()]);

        $response = $this->getJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $task->id,
                     'title' => $task->title,
                     'description' => $task->description,
                     'status' => $task->status,
                     'priority' => $task->priority,
                     'deadline' => $task->deadline,
                 ]);
    }

    public function test_can_start_task()
    {
        $task = Task::factory()->for($this->user)->create(['status' => 'Pending']);

        $response = $this->postJson("/api/v1/tasks/{$task->id}/start");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Task started successfully.',
                     'task' => [
                         'id' => $task->id,
                         'status' => 'In Progress',
                     ]
                 ]);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->for($this->user)->create();

        $updatedData = [
            'title' => 'Updated Task Title',
            'description' => 'Updated Description',
            'status' => 'Em Andamento',
            'priority' => 'Alta',
        ];

        $response = $this->putJson("/api/v1/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Task updated successfully.',
                     'task' => [
                         'title' => 'Updated Task Title',
                         'description' => 'Updated Description',
                         'status' => 'Em Andamento',
                         'priority' => 'Alta',
                     ]
                 ]);
    }

    public function test_can_complete_task()
    {
        $task = Task::factory()->for($this->user)->create(['status' => 'Em Andamento']);

        $response = $this->postJson("/api/v1/tasks/{$task->id}/complete");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Task completed successfully.',
                     'task' => [
                         'id' => $task->id,
                         'status' => 'Completed',
                     ]
                 ]);
    }
}
