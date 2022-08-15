<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function find(string $email): ?User;

    public function findAll(): array;

    public function create(array $data): User;

    public function update(string $email, array $data): User;

    public function delete(string $email): bool;
}
