<?php

namespace Tests\Feature\Todo;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_create_a_todo_item()
    {
        //Arrange
        /** @var User $user */
        $user = User::factory()->createOne();

        /** @var User $user */
        $assignedTo = User::factory()->createOne();

        $this->actingAs($user);

        //Act
        $response = $this->post(route('todo.store'), [
            'title' => 'Todo item',
            'description' => 'Todo item description',
            'assigned_to_id' => $assignedTo->id
        ]);

        //Assert
        $response->assertRedirect(route('todo.index'));
        $this->assertDatabaseHas('todos',[
            'title' => 'Todo item',
            'description' => 'Todo item description',
            'assigned_to_id' => $assignedTo->id
        ]);
    }

    /** @test */
    public function it_should_be_able_to_add_a_file_to_the_todo_item()
    {
        Storage::fake('public');

        /** @var User $user */
        $user = User::factory()->createOne();

        $this->actingAs($user);

        $response = $this->post(route('todo.store'), [
            'title' => 'Todo item',
            'description' => 'Todo item description',
            'assigned_to_id' => $user->id,
            'file' => UploadedFile::fake()->image('image1.png')
        ]);

        Storage::disk('public')->assertExists('todo/image1.png');
        $this->assertDatabaseHas('todos', [
            'file' => 'todo/image1.png'
        ]);
    }

}
