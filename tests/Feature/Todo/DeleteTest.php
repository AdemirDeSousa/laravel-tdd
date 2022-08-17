<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_delete_a_todo()
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $todo = Todo::factory()->createOne();

        $this->actingAs($user);
        $this->delete(route('todo.destroy', $todo))->assertRedirect(route('todo.index'));

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id
        ]);
    }
}
