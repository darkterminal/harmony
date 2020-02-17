<?php

session_start();
date_default_timezone_set('Asia/Jakarta');
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
use DI\Container;
use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\Adapter\ServerConstAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;


require_once dirname(__DIR__) . '/vendor/autoload.php';

$adapters = [
    new EnvConstAdapter(),
    new ServerConstAdapter(),
];

$repository = RepositoryBuilder::create()
->withReaders($adapters)
->withWriters($adapters)
->immutable()
->make();

Dotenv::create($repository, dirname(__DIR__), null)->load();

$container = new \DI\Container();
AppFactory::setContainer($container);
$app = AppFactory::create();
$responseFactory = $app->getResponseFactory();

$settings = [
    "displayErrorDetails" => env('DISPLAY_ERROR'),
    "debug" => env('DEBUG'),
    'whoops.editor' => env('EDITOR'),
    "db" => [
        "driver" => env('DB_DRIVER'),
        "host" => env('DB_HOST'),
        "database" => env('DB_NAME'),
        "username" => env('DB_USER'),
        "password" => env('DB_PASS'),
        "charset" => "utf8",        
        "collation" => "utf8_unicode_ci",
        "prefix" => ""        
    ],
    'nate.email' => [
        'host' => env('EMAIL_HOST'),
        'username' => env('EMAIL_USERNAME'),
        'password' => env('EMAIL_PASSWORD'),
        'port' => env('EMAIL_PORT'),
        'secure' => env('EMAIL_SECURE'),
    ],
    'app_url' => env('APP_HOST'),
    "storage" => dirname(__DIR__).'/storage/public'
];

$container->set('csrf', function (\Psr\Container\ContainerInterface $container) use ($responseFactory) {
    return new Guard($responseFactory);
});

require_once dirname(__DIR__) . '/bootstrap/container.php';
require_once dirname(__DIR__) . '/config/app.container.php';

$app->addRoutingMiddleware();
require_once dirname(__DIR__) . '/bootstrap/middleware.php';


VarDumper::setHandler(function ($var) {

    $cloner = new VarCloner;

    $htmlDumper = new HtmlDumper;
    $htmlDumper->setStyles([
        'default' => 'background-color:#18171B; color:#FF8400; line-height:1.2em; font:12px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: break-all',
        'num' => 'font-weight:bold; color:#1299DA',
        'const' => 'font-weight:bold',
        'str' => 'font-weight:bold; color:#6de89e',
        'note' => 'color:#1299DA',
        'ref' => 'color:#A0A0A0',
        'public' => 'color:#FFFFFF',
        'protected' => 'color:#FFFFFF',
        'private' => 'color:#FFFFFF',
        'meta' => 'color:#B729D9',
        'key' => 'color:#6de89e',
        'index' => 'color:#1299DA',
        'ellipsis' => 'color:#FF8400',
    ]);

    $dumper = PHP_SAPI === 'cli' ? new CliDumper : $htmlDumper;

    $dumper->dump($cloner->cloneVar($var));

});

// Add Twig-View Middleware
$app->add(TwigMiddleware::createFromContainer($app));
$app->add('csrf');
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Set the Not Found Handler
$errorMiddleware->setErrorHandler(HttpNotFoundException::class, function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
    $response = new Response();
    $response->getBody()->write('404 NOT FOUND');

    return $response->withStatus(404);
});

// Set the Not Allowed Handler
$errorMiddleware->setErrorHandler(HttpMethodNotAllowedException::class, function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
    $response = new Response();
    $response->getBody()->write('405 NOT ALLOWED');

    return $response->withStatus(405);
});

v::with('App\\Support\\Validation\\Rules\\');

require_once dirname(__DIR__) . '/routes/web.php';
