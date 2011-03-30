<?php

namespace Troupe;

class Settings {
  
  private $options = array(
    'vendor_dir' => 'vendor'
  );
  
  function __construct(array $options = array()) {
    $this->options['data_dir'] = realpath(__DIR__ . '/../../data');
    $this->options = array_merge($this->options, $options);
  }
  
  function get($key) {
    return $this->options[$key];
  }

}
