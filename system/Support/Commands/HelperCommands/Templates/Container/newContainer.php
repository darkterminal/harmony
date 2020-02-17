<?php
$container->set('auth', function(\Psr\Container\ContainerInterface $container) {
    return new \App\Support\Auth\Auth;
});

$container->set('AuthController', function(\Psr\Container\ContainerInterface $container) {
    return new \App\Controllers\Auth\AuthController($container);
});

$container->set('PasswordController', function(\Psr\Container\ContainerInterface $container) {
    return new \App\Controllers\Auth\PasswordController($container);
});
