<?php

namespace Troupe;

class DataStore {
  
  private
    $data_dir,
    $data = array();
  
  function __construct($data_directory) {
    $this->data_dir = $data_directory;
    $data_file = $this->data_dir . '/troupe.dat';
    if (file_exists($data_file)) {
      $this->data = unserialize(file_get_contents($data_file));
    }
  }
  
  function __destruct() {
    if (file_exists($this->data_dir)) {
      fwrite(
        fopen($this->data_dir . '/troupe.dat', 'w'), 
        serialize($this->data)
      );
    }
  }  
  
  
  function get($collection, $key) {
    if (isset($this->data[$collection][$key])) {
      return $this->data[$collection][$key];
    }
    return null;
  }
  
  function set($collection, $key, $value) {
    if (!isset($this->data[$collection])) {
      $this->data[$collection] = array();
    }
    $this->data[$collection][$key] = $value;
  }
  
}
