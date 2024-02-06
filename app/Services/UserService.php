<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function authorize(array $attributes)
    {
        $user = $this->userRepository->getUserByLogin($attributes['login']);

        if (is_null($user)) {
            return redirect()->back()->withErrors("Неправильный логин или пароль")->withInput();
        }
        if (!Hash::check($attributes['password'], $user->password)) {
            return redirect()->back()->withErrors("Неправильный логин или пароль")->withInput();
        }

        Auth::login($user);

        return redirect(route('main_page'));
    }

    public function register(array $attributes)
    {
        $isUserExists = $this->userRepository->exist($attributes['email']);
        if ($isUserExists) {
            return redirect()->back()->withErrors("User already exists")->withInput();
        }
        if ($attributes['password'] != $attributes['confpass']) {
            return redirect()->back()->withErrors('Passwords are different');
        }

        $attributes['password'] = Hash::make($attributes['password']);
        $this->userRepository->create($attributes);

        return redirect()->back()->with('success', 'Registered!');
    }
}
