<?php
namespace Controller;
use Src\View;

class Site
{
    public function index(): string
    {
        return (new View())->render('site.hello', ['message' => 'Библиотека — главная']);
    }

    public function hello(): string
    {
        return new View('site.hello', ['message' => 'Hello from library!']);
    }
}