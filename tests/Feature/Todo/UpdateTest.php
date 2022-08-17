<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_update_a_todo()
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $todo = Todo::factory()->createOne();

        $this->actingAs($user);

        $this->put(route('todo.update', $todo), [
            'title' => 'Todo item updated',
            'description' => 'Todo item description updated',
            'assigned_to_id' => $user->id,
        ])->assertRedirect(route('todo.index'));

        $todo->refresh();

        $this->assertEquals('Todo item updated', $todo->title);
        $this->assertEquals('Todo item description updated', $todo->description);
    }
}
