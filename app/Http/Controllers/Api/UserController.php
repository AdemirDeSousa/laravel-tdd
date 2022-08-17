<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $usersRepo;

    public function __construct(UserRepositoryInterface $usersRepo)
    {
        $this->usersRepo = $usersRepo;
    }

    public function index()
    {
        $users = $this->usersRepo->paginate();

        return UserResource::collection(collect($users->items()))
            ->additional([
                'meta' => [
                    'total' => $users->total(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'first_page' => $users->firstPage(),
                    'per_page' => $users->perPage()
                ]
            ]);
    }
}
