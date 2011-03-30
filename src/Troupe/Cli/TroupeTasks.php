<?php

namespace Troupe\Cli;

use \Troupe\SystemUtilities;
use \Troupe\Manager;

class TroupeTasks implements Tasks {

  private $controller, $system_utilities, $manager;
  
  function __construct(SystemUtilities $system_utilities, Manager $manager) {
    $this->system_utilities = $system_utilities;
    $this->manager = $manager;
  }
  
  function getTaskNamespace() {
    return '';
  }
  
  function setController($controller) {
    $this->controller = $controller;
  }
  
  function taskAssemble() {
    $this->manager->importDependencies();
  }
  
}
