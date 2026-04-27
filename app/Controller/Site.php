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
    if ($request->method === 'POST') {
        $validator = new \Src\Validator\Validator($request->all(), [
            'name'     => ['required'],
            'login'    => ['required', 'unique:users,login'],
            'password' => ['required']
        ], [
            'required' => 'Поле :field пусто',
            'unique'   => 'Поле :field должно быть уникальным'
        ]);

        if ($validator->fails()) {
            return new View('site.signup', [
                'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)
            ]);
        }

        if (User::create($request->all())) {
            app()->route->redirect('/login');
        }
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