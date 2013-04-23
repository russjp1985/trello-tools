<?php
require dirname(dirname(__FILE__))."/lib/autoload.php";

spl_autoload_register(function($class_name) {
    if ($class_name == 'TPTest') {
        require dirname(dirname(__FILE__))."/tpt/tpt/tpt.php";
    }
});

spl_autoload_register(function($class_name) {
    if (preg_match('/[a-z]+Test$/', $class_name)) {
        $class_name = str_replace('Test', '', $class_name);
        $path = dirname(__FILE__).'/'.class_to_file($class_name);
        if (file_exists($path) && is_readable($path)) {
            require $path;
        }
    }
});
