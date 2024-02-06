<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect()->to(route('main_page'));
        }

        return view('enterviews.register', ['title' => "Регистрация"]);
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->to(route('main_page'));
        }

        return view('enterviews.login', ['title' => "Авторизация",]);
    }

    public function authorizeHandler(Request $request)
    {
        $attributes = $request->only(['login', 'password',]);

        return $this->service->authorize($attributes);
    }

    public function registerHandler(UserRegisterRequest $request): RedirectResponse
    {
        $attributes = $request->validated();

        return $this->service->register($attributes);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->to(route('login'));
    }
}
