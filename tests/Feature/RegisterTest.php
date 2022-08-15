<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_register_as_a_new_user()
    {
        //Arrange
        $this->withoutExceptionHandling();

        //Act
        $return = $this->post(route('register'), [
            'name' => 'Ademir',
            'email' => 'jadsqj@gmail.com',
            'email_confirmation' => 'jadsqj@gmail.com',
            'password' => 'password'
        ]);

        //Assert
        $return->assertRedirect('dashboard');

        $this->assertDatabaseHas('users',[
            'name' => 'Ademir',
            'email' => 'jadsqj@gmail.com',
        ]);

        /**
         * @var User $user
         */
        $user = User::whereEmail('jadsqj@gmail.com')->firstOrFail();

        $this->assertTrue(Hash::check('password', $user->password), 'checked user password');

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function name_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
               'name' => __('validation.required', ['attribute' => 'name'])
            ]);
    }

    /** @test */
    public function name_should_have_a_max_of_255_characters()
    {
        $this->post(route('register'), [
            'name' => str_repeat('a', 256)
        ])->assertSessionHasErrors([
            'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])
        ]);
    }

    /** @test */
    public function email_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'email' => __('validation.required', ['attribute' => 'email'])
            ]);
    }

    /** @test */
    public function email_should_be_valid_email()
    {
        $this->post(route('register'), [
            'email' => 'invalid-email'
        ])
            ->assertSessionHasErrors([
                'email' => __('validation.email', ['attribute' => 'email'])
            ]);
    }

    /** @test */
    public function email_should_be_unique()
    {
        User::factory()->create([
            'email' => 'teste@gmail.com'
        ]);

        $this->post(route('register'), [
            'name' => 'Ademir',
            'email' => 'teste@gmail.com'
        ])
            ->assertSessionHasErrors([
                'email' => __('validation.unique', ['attribute' => 'email'])
            ]);
    }

    /** @test */
    public function email_should_be_confirmed()
    {
        $this->post(route('register'), [
            'name' => 'Ademir',
            'email' => 'teste@gmail.com',
            'email_confirmation' => ''
        ])
            ->assertSessionHasErrors([
                'email' => __('validation.confirmed', ['attribute' => 'email'])
            ]);
    }

    /** @test */
    public function password_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'password' => __('validation.required', ['attribute' => 'password'])
            ]);
    }
}
