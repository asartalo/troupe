<?php

namespace Troupe\Tests;

class ArgvParser {
  
  private $script_name;
  
  function __construct($script_name) {
    $this->script_name = $script_name;
  }
  
  function parse($args) {
    $args_array = $args ?
      preg_split(
        '/[\s]*\'([^\']+)\'[\s]*|[\s]*"([^"]+)"[\s]*|\s+/', $args, 
        0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
      ) :
      array();
    array_unshift($args_array, $this->script_name);
    return $args_array;
  }
  
}