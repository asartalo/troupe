<?php

namespace Troupe\Cli;
use \Troupe\Cli\Command;

class Interpreter {
  
  function __construct() {
    
  }
  
  function interpret(array $args) {
    $result = array();
    $result['caller'] = array_shift($args);
    $flags = array();
    $arguments = array();
    $is_command_found = false;
    foreach ($args as $arg) {
      if ($is_command_found) {
        $arguments[] = $arg;
      } elseif (strpos($arg, '--') === 0) {
        $flags[] = substr($arg, 2);
      } else {
        $colon = strpos($arg, ':');
        if ($colon > 0) {
          $result['namespace'] = substr($arg, 0, $colon);
          $result['command'] = substr($arg, $colon + 1);
        } else {
          $result['command'] = $arg;
        }
        $is_command_found = true;
      } 
    }
    $result['flags']     = $flags;
    $result['arguments'] = $arguments;
    return new Command($result);
  }

}
