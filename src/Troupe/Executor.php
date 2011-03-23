<?php

namespace Troupe;

class Executor {
  
  function execute($command) {
    exec($command, $output);
    return $output;
  }
  
  function system($command) {
    echo $command. "\n";
    return system($command);
  }

}
