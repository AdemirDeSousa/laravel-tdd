<?php

namespace App\Repositories\Eloquent\Users;

use App\Models\User;
use App\Repositories\Contracts\PaginationInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Exceptions\NotFoundException;
use App\Repositories\Presenters\PaginatePresenter;

class UserRepository implements UserRepositoryInterface
{
    /** @var User  */
    protected $model;

    public function __construct(User $users)
    {
        $this->model = $users;
    }

    public function findAll(): array
    {
        return $this->model->get()->toArray();
    }

    public function paginate(int $page = 10): PaginationInterface
    {
        $users = $this->model->paginate(10);

        return new PaginatePresenter($users);
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(string $email, array $data): User
    {
        $user = $this->find($email);

        $user->update($data);

        $user->refresh();

        return $user;
    }

    public function delete(string $email): bool
    {
        $user = $this->find($email);

        if(!$user){
            throw new NotFoundException('User Not Found');
        }

        return $user->delete();
    }

    public function find(string $email): ?User
    {
        return $this->model->where('email', $email)->first();

    }
}

