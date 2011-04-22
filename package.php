<?php

// To run:
// $ php package.php > package.xml

die('Not yet ready. Still need to organize source files and dependencies. Bummer.');

$sources = '';

$src_dir = __DIR__ . DIRECTORY_SEPARATOR . 'Troupe';

$iterator = new DirectoryIterator($src_dir);
$source_files = array();

function recursivelyGetFiles($it, $cut = '') {
  global $source_files;
  $cutpart = strlen($cut);
  foreach ($it as $item) {
  
    if ($item->isDot() || $it->getPath() == $item->getPathName()) {
      continue;
    }
    if ($item->isFile()) {
      $key = ($cutpart > 0) ? substr($item->getPathName(), $cutpart) : $item->getPathName();
      $source_files[$key] = $item->getFileName();
    }
    if ($item->isDir()) {
      recursivelyGetFiles(new DirectoryIterator($item->getPathName()), $cut);
    }
  }
}

recursivelyGetFiles($iterator, __DIR__ . DIRECTORY_SEPARATOR);

foreach ($source_files as $path => $file) {
  $sources .= sprintf('<file role="php" baseinstalldir="/" name="%s" />' . "\n", $path);
}

include 'package.xml.tpl';
