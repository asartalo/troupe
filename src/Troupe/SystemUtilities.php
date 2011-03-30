<?php

namespace Troupe;

class SystemUtilities {
  
  function fclose($handle) {
    return fclose($handle);
  }
  
  function fileExists($file) {
    return file_exists($file);
  }
  
  function fopen($filename, $mode) {
    return fopen($filename, $mode);
  }
  
  function fwrite($handle, $string, $length = null) {
    if (is_null($length)) {
      return fwrite($handle, $string);
    }
    return fwrite($handle, $string, $length);
  }
  
  function stream_context_create($options = array(), $params = array()) {
    return stream_context_create($options, $params);
  }
  
  function stream_get_contents($handle, $maxlength = -1, $offset = -1) {
    return stream_get_contents($handle, $maxlength, $offset);
  }
  
  function includeFile($file) {
    return include $file;
  }
  
  function mkdir($pathname, $mode = 0777, $recursive = false, $context = null) {
    if ($context) {
      return mkdir($pathname, $mode, $recursive, $context);
    }
    return mkdir($pathname, $mode, $recursive);
  }
  
  function out($string) {
    echo $string, "\n";
  }
  
  function readlink($link) {
    return readlink($link);
  }
  
  function symlink($target, $link) {
    symlink($target, $link);
  }
  
  function umask($mode = null) {
    if ($mode) {
      return umask($mode);
    }
    return umask();
  }
  
  function unlink($file) {
    return unlink($file);
  }
  
}
