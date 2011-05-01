<?php

namespace Troupe;

class Executor {
  
  function execute($command, $return_status = false) {
    exec($command, $output, $status);
    if ($return_status) {
      return $status;
    }
    return $output;
  }
  
  function system($command, $return_status = false) {
    echo $command. "\n";
    $output = system($command, $status);
    if ($return_status) {
      return $status;
    }
    return $output;
  }

}
