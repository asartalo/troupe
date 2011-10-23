<?php
require_once realpath(__DIR__ . '/../src/SplClassLoader.php');
require_once __DIR__ . '/Troupe/Tests/TestCase.php';
$src_path = realpath(__DIR__ . '/../src/');
$classLoader = new SplClassLoader('Troupe', $src_path);
$classLoader->register();
$classLoader = new SplClassLoader('Pimple', $src_path . '/Pimple/lib');
$classLoader->register();
require_once 'Cibo/Cibo.php';