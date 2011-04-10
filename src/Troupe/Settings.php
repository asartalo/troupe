<?php

namespace Troupe;

class Settings {
  
  private $settings = array();
  
  function __construct(array $options = array()) {
    $this->settings = $options;
  }
  
  function get($key) {
    $val = $this->settings[$key];
    preg_match_all('/\{([^\}]+)\}/', $val, $matches);
    $vars = $matches[0];
    $keys = $matches[1];
    for ($i = 0, $count = count($vars); $i < $count; $i++) {
      if (isset($this->settings[$keys[$i]]) && $keys[$i] !== $key) {
        $val = str_replace($vars[$i], $this->settings[$keys[$i]], $val);
      }
    }
    return $val;
  }

}
