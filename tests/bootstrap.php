<?php

$composerAutoloaderPath = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($composerAutoloaderPath)) {
    require $composerAutoloaderPath;
}

require __DIR__ . '/cases/Field.php';
require __DIR__ . '/cases/FieldList.php';
