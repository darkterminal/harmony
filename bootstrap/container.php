<?php
/** 
 * -----------------------------------------------------
 *      SELAMAT DATANG DI HARMONY FRAMWORK             |
 * -----------------------------------------------------
 * 
 * @author Imam Ali Mustofa <bettadevindonesia@gmail.com>
 * @license MIT
 * @category Framework
 * @version 1.0.0
 * 
 * Framework ini dibangun dengan Slim Framework versi 3
 * dibuat untuk mempermudah pekerjaan sebagai web developer. 
 * 
 */

/**
 * DANGER ZONE !!!
 */



$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($settings['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container->set('notFoundHandler', function (\Psr\Container\ContainerInterface $container) {
    return function ($request, $response) use ($container) {
        return $container['view']->render($response, 'error/404.twig', ['status' => 404, 'message' => 'The page you seek does not exist.']);
    };
});

$container->set('notAllowedHandler', function (\Psr\Container\ContainerInterface $container) {
    return function ($request, $response, $methods) use ($container) {
        return $container['view']->render($response, 'error/404.twig', ['status' => 405, 'message' => 'The page you seek does not allowed for you.']);
    };
});

$container->set('db', function (\Psr\Container\ContainerInterface $container) use ($capsule) {
    return $capsule;
});

$container->set('mailer', function(\Psr\Container\ContainerInterface $container) {
    $mailer = new Nette\Mail\SmtpMailer($container['settings']['nate.email']);
    return $mailer;
});

$container->set('phpmig.adapter', function (\Psr\Container\ContainerInterface $container) {
    return new Adapter\Illuminate\Database($container['db'], 'migrations');
});

$container->set('phpmig.migrations_path', function () {
    return dirname(__DIR__) . '/app/Database/migrations';
});

$container->set('schema', function () {
    return Capsule::schema();
});

$container->set('flash', function(\Psr\Container\ContainerInterface $container){
    return new \Slim\Flash\Messages;
});

$container->set('view', function(\Psr\Container\ContainerInterface $container) {

    $view = new \Slim\Views\Twig(dirname(__DIR__) . '/resources/views', [
            'debug' => true,
            'cache' => false,
    ]);
    $view->addExtension(new \Harmony\Support\Views\DebugExtension);

    require dirname(__DIR__) . '/system/Support/Helpers/bootstrap.php';

    return $view;
});

$container->set('validator', function(\Psr\Container\ContainerInterface $container) {
    return new \App\Support\Validation\Validator;
});
