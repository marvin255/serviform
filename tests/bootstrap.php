<?php

$composerAutoloaderPath = dirname(__DIR__).'/vendor/autoload.php';
if (file_exists($composerAutoloaderPath)) {
    require $composerAutoloaderPath;
} else {
    require __DIR__.'/../lib/Autoloader.php';
}

require __DIR__.'/cases/Field.php';
require __DIR__.'/cases/FieldList.php';
require __DIR__.'/cases/Validator.php';
