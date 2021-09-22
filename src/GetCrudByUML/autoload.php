<?php

define("DB_INI", "../config/escritordesoftware_bd.ini");

function autoload($classe) {
    
    $prefix = 'GetCrudByUML';
    $base_dir = './GetCrudByUML';
    $len = strlen($prefix);
    if (strncmp($prefix, $classe, $len) !== 0) {
        return;
    }
    $relative_class = substr($classe, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
    
}

spl_autoload_register('autoload');
