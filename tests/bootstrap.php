<?php
require_once realpath(__DIR__ . '/../src/SplClassLoader.php');
$path = realpath(__DIR__ . '/../src/');
$classLoader = new SplClassLoader('Troupe', $path);
$classLoader->register();
