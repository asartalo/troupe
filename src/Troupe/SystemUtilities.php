<?php

namespace Troupe;

class SystemUtilities {
  
  function symlink($target, $link) {
    echo "target: $target \n link: $link\n";
    symlink($target, $link);
  }
  
  function readlink($link) {
    return readlink($link);
  }
  
  function execute($command) {
    exec($command, $output);
    return $output;
  }
  
  function system($command) {
    return system($command);
  }
  
  function fileExists($file) {
    return file_exists($file);
  }
  
  function out($string) {
    echo $string, "\n";
  }
  
  function includeFile($file) {
    return include $file;
  }
  
}
