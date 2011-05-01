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
  
  function system($command) {
    echo $command. "\n";
    return system($command);
  }

}
