<?php

namespace Troupe;

class Settings {
  
  private $options = array(
    'vendor_dir' => 'vendor'
  );
  
  function __construct(array $options) {
    $this->options = array_merge($this->options, $options);
  }
  
  function get($key) {
    return $this->options[$key];
  }

}
