<?php

namespace Troupe\File;

class Factory {
  
  private $known_types = array(
    'php' => 'Php',
    'ini' => 'Ini'
  );
  
  function getFile($file) {
    if (file_exists($file)) {
      $ext = pathinfo($file, PATHINFO_EXTENSION);
      if (isset($this->known_types[$ext])) {
        $class = 'Troupe\\File\\' . $this->known_types[$ext];
        return new $class($file);
      }
      return new Common($file);
    }
    return new NonExistentFile($file);
  }
  
}
