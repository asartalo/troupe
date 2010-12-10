<?php

namespace Troupe\Source;

use \Troupe\Source\Source;
use \Troupe\SystemUtilities;

abstract class AbstractSource implements Source {
  
  protected $system_utilities, $url, $data_directory;
  
  function __construct($url, SystemUtilities $system_utilities, $data_directory) {
    $this->system_utilities = $system_utilities;
    $this->url = $url;
    $this->data_directory = $data_directory;
  }
  
  function getDataDir() {
    return $this->data_directory . '/' . md5($this->url);
  }
  
}
