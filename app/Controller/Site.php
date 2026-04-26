<?php
namespace Controller;

use Model\User;
use Src\Auth\Auth;
use Src\Request;
use Src\View;

class Site
{
    public function home(Request $request): string
    {
        return new View('site.home');
    }

    public function signup(Request $request): string
    {
        if ($request->method === 'POST' && User::create($request->all())) {
            app()->route->redirect('/login');
        }
        return new View('site.signup');
    }

    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        if (Auth::attempt($request->all())) {
            app()->route->redirect('/home');
        }
        return new View('site.login', ['message' => 'Неверный логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/login');
    }
}