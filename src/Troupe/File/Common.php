<?php

namespace Troupe\File;

// TODO: Change this to abstract class
class Common implements File {
  
  private $path;
  
  function __construct($path) {
    if (!file_exists($path)) {
      throw new Exception(
        "Unable to instantiate. The file '$path' does not exist."
      );
    }
    $this->path = $path;
  }
  
  function getContents() {
    return file_get_contents($this->path);
  }
  
  function getPath() {
    return $this->path;
  }
  
  function getType() {
    return '';
  }
  
  function isFileExists() {
    return true;
  }
  
  function setContents($contents) {
    throw Exception('NYI!');
  }
  
  function save() {
    throw Exception('NYI!');
  }

}
