<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TasksTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use DatabaseMigrations;

    /** @test */
    public function user_can_create_tasks()
    {
        $user = factory(User::class)->create();
        $task = [
            'text' => 'New task text',
            'user_id' => $user->id
        ];

        $response = $this->actingAs($user)->json('POST', 'api/task', $task);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', $task);
    }

    /** @test */
    public function guest_users_can_not_create_tasks()
    {

        $task = [
            'text' => 'new text',
            'user_id' => 1
        ];

        $response = $this->json('POST', 'api/task', $task);

        $response->assertstatus(401);
        $this->assertDatabaseMissing('tasks', $task);
    }

    /** @test */
    public function user_can_delete_tasks()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'text' => 'task to delete',
            'user_id' => $user->id,
            'is_completed' => false
        ]);

        $response = $this->actingAs($user)->json('DELETE', "api/task/$task->id");

        $response->assertstatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function user_can_complete_tasks()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'text' => 'task to complete',
            'user_id' => $user->id,
            'is_completed' => false ,
        ]);

        Passport::actingAs($user);
        $response = $this->json('PUT', "api/task/$task->id", ['is_completed' => true]);

        $response->assertstatus(200);
        $this->assertNotNull($task->fresh()->is_completed);
    }

}
