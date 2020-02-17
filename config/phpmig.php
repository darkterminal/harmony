<?php

date_default_timezone_set("Asia/Jakarta");
require_once 'bootstrap/app.php';
use Phpmig\Adapter;


$container->set('phpmig.adapter', function(\Psr\Container\ContainerInterface $container) {
    return new Adapter\Illuminate\Database($container->get('db'), 'migrations');
});

$container->set('phpmig.migrations_path', function () {
    return dirname(__DIR__) . '/app/Database/migrations';
});

$container->set('phpmig.migrations_template_path', function(\Psr\Container\ContainerInterface $container) {
    return $container['phpmig.migrations_path'] . DIRECTORY_SEPARATOR . '.template.php';
});

$container->set('schema', function(\Psr\Container\ContainerInterface $container) {
    return Capsule::schema();
});

return $container;
