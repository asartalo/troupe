<?php

namespace Troupe\Cli;

use \Troupe\SystemUtilities;
use \Troupe\Manager;
use \Troupe\Output;
use \Silly\Tasks;

class TroupeTasks implements Tasks {

  private $controller, $output, $manager;

  function __construct(Output $output, Manager $manager) {
    $this->output = $output;
    $this->manager = $manager;
  }

  function getTaskNamespace() {
    return '';
  }

  function setController(\Silly\Controller $controller) {
    $this->controller = $controller;
  }

  function taskAssemble() {
    $this->manager->importDependencies();
  }

  function taskList() {
    $this->manager->outputDependencies();
  }

  function taskUpdate() {
    $this->manager->updateDependencies();
  }

}
