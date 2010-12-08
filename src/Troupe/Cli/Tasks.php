<?php

namespace Troupe\Cli;

interface Tasks {
  function getTaskNamespace();
  function setController($controller);
}
