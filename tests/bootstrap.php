<?php
require_once realpath(__DIR__ . '/../src/SplClassLoader.php');
require __DIR__ . '/Troupe/Tests/TestCase.php';
$classLoader = new SplClassLoader('Troupe', realpath(__DIR__ . '/../src/'));
$classLoader->register();
