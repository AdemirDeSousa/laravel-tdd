<?php

namespace App\Http\Controllers;

use App\Models\User;

class RegisterController extends Controller
{
    public function __invoke()
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|confirmed',
            'password' => 'required'
        ]);

        /**
         * @var User $user
         */
        $user = User::query()->create([
            'name' => request()->name,
            'email' => request()->email,
            'password' => bcrypt(request()->password)
        ]);

        auth()->login($user);

        return redirect('dashboard');

    }
}
