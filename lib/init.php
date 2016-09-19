<?php

require_once (dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');

function __autoload ($class_name) {
    $lib_path = __DIR__ . DIRECTORY_SEPARATOR .strtolower($class_name).'.class.php';
    $controllers_path = dirname(__DIR__).DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.str_replace('controller', '', strtolower($class_name)).'.controller.php';
    $model_path = dirname(__DIR__).DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.strtolower($class_name).'.php';
    try {
        if (file_exists($lib_path)) {
            require_once ($lib_path);
        } elseif (file_exists($controllers_path)) {
            require_once ($controllers_path);
        } elseif (file_exists($model_path)) {
            require_once ($model_path);
        } else {
            throw new Exception('Failed to include class '. $class_name);
        }
    } catch (Exception $err) {
        echo $err->getMessage().'<br>';
    }
}

function __($key, $default_value = '') {

    return Lang::get($key, $default_value);

}
