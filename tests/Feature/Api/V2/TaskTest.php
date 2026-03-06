<?php

namespace Tests\Feature\Api\V2;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_get_list_of_tasks(): void
    {
        // Arrange: create 2 fake tasks
        
        $user = User::factory()->create();
        $this->actingAs($user);
        // Act: Make a GET request to the endpoint
        $tasks = Task::factory()->count(2)->create([
            'user_id' => $user->id
        ]);
        $response = $this->getJson('/api/v1/tasks');

        // Assert: status is 200 OK and data has 2 items
        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                ['id', 'name', 'is_completed']
            ]
        ]);
    }

    public function test_user_can_get_single_task(): void
    {
        // Arange: crate a task
        $task = Task::factory()->create();

        // Act: Make a GET request to the endpoint with task ID
        $response = $this->getJson('/api/v1/tasks/' . $task->id);

        // Assert: response contains the correct task data
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'is_completed']
        ]);
        $response->assertJson([
            'data' => [
                'id' => $task->id,
                'name' => $task->name,
                'is_completed' => $task->is_completed,
            ]
        ]);
    }

        // `PUT /tasks/{id}` → update existing task
    public function test_user_can_update_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson('/api/v1/tasks/' . $task->id, [
            'name' => 'Updated Task'
        ]);

        $response->assertOk();
        $response->assertJsonFragment([
            'name' => 'Updated Task'
        ]);
    }
}