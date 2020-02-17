<?php
/**************************************************************
 *                  DON'T REMOVE THIS LINE !!!                *
 * ************************************************************
 * 
 * 
 * Ini adalah middleware default yang digunakan oleh sistem
 * Silahkan anda buat middleware buatan anda di baris paling bawah
 * 
 */
if($settings['debug'] === true){
    $app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware($app));
}
