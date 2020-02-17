<?php

/**
 * 
 * Silahkan definisakn container buatan anda di bawah area ini.
*/

$container->set('HomeController', function(\Psr\Container\ContainerInterface $container) {
	return new \App\Controllers\HomeController($container);
});

