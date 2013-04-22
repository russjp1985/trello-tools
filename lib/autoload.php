<?php

function class_to_file($class_name) {
    return implode('/', explode('_', $class_name)).'.php';
}

spl_autoload_register(function($class_name) {
    $path = dirname(__FILE__).'/'.class_to_file($class_name);
    if (file_exists($path) && is_readable($path)) {
        require $path;
    }
});
