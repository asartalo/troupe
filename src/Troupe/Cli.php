<?php

namespace Troupe;

class Cli {
  
  private $interpreter, $executor, $cwd, $out_first;
  
  function __construct(
    \Troupe\Cli\Interpreter $interpreter,
    \Troupe\Cli\Executor $executor,
    $cwd
  ) {
    $this->interpreter = $interpreter;
    $this->executor = $executor;
    $this->cwd = $cwd;
  }
  
}