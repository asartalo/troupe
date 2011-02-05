<?php

namespace Troupe\Reader;

use \Troupe\File\File;
use \Troupe\SystemUtilities;

class Factory {
  
  private 
    $system_utilities,
    $known_readers = array(
      'php' => 'Php',
      'ini' => 'Ini',
    );
  
  function __construct(SystemUtilities $system_utilities) {
    $this->system_utilities = $system_utilities;
  }
  
  function getReader(File $file) {
    if (isset($this->known_readers[$file->getType()])) {
      $class = "Troupe\\Reader\\{$this->known_readers[$file->getType()]}";
      return new $class($file, $this->system_utilities);
    }
    return new Unknown;
  }

}
