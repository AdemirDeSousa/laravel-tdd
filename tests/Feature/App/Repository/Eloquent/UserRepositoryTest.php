<?php

namespace Tests\Feature\App\Repository\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\Users\UserRepository;
use App\Repositories\Exceptions\NotFoundException;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{

    protected $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(new User());

        parent::setUp();
    }

    public function test_repository_interface()
    {
        $this->assertInstanceOf(UserRepositoryInterface::class, $this->userRepository);
    }

    public function test_find_all_empty()
    {
        $response = $this->userRepository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(0, $response);
    }

    public function test_find_all()
    {
        User::factory()->count(10)->create();

        $response = $this->userRepository->findAll();

        $this->assertCount(10, $response);
    }

    public function test_create()
    {
        $data = [
            'name' => 'Ademir',
            'email' => 'teste@gmail.com',
            'password' => bcrypt('password'),
        ];

        $user = $this->userRepository->create($data);

        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'email' => 'teste@gmail.com',
        ]);

    }

    public function test_create_exception()
    {
        $this->expectException(QueryException::class);

        $data = [
            'name' => 'Ademir',
            'password' => bcrypt('password'),
        ];

        $this->userRepository->create($data);
    }

    public function test_update()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Ademir editado',
        ];

        $response = $this->userRepository->update($user->email, $data);

        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $response);
        $this->assertDatabaseHas('users', [
            'name' => 'Ademir editado',
        ]);
    }

    public function test_delete()
    {
        $user = User::factory()->create();

        $deleted = $this->userRepository->delete($user->email);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', [
            'name' => $user->email,
        ]);
    }

    public function test_delete_exception()
    {
        $this->expectException(NotFoundException::class);

        $this->userRepository->delete('fake_email');
    }

    public function test_find()
    {
        $user = User::factory()->create();

        $response = $this->userRepository->find($user->email);

        $this->assertInstanceOf(User::class, $response);
    }

    public function test_find_not_found()
    {
        $response = $this->userRepository->find('fake_email');

        $this->assertNull($response);
    }
}
