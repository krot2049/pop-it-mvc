<?php
return [
    'routeAppMiddleware' => [
    'csrf'         => \Middlewares\CSRFMiddleware::class,
    'trim'         => \Middlewares\TrimMiddleware::class,
    'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
],
    'auth'     => \Src\Auth\Auth::class,
    'identity' => \Model\User::class,
    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
    ],
    'routeAppMiddleware' => [
        'trim'         => \Middlewares\TrimMiddleware::class,
        'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
    ],
    'validators' => [
        'required' => \Validators\RequireValidator::class,
        'unique'   => \Validators\UniqueValidator::class,
    ]
];