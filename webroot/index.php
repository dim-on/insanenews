<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('VIEWS_PATH', dirname(__DIR__). DS . 'views');

require_once (dirname(__DIR__). DIRECTORY_SEPARATOR . 'lib'. DIRECTORY_SEPARATOR .'init.php');
//
//    $router = new Router($_SERVER['REQUEST_URI']);
//
//echo "<pre>";
//print_r('Route: '.$router->getRoute().PHP_EOL);
//print_r('Language: '.$router->getLanguage().PHP_EOL);
//print_r('Controller: '.$router->getController().PHP_EOL);
//print_r('Action to be called: '.$router->getMethodPrefix().$router->getAction().PHP_EOL);
//echo 'Params: ';
//print_r($router->getParams());

//$test = App::$db->query('select * from pages');
//echo "<pre>";
//print_r($test);

session_start();

try {

    App::run($_SERVER['REQUEST_URI']);

} catch (Exception $e) {
    echo $e->getMessage();
}