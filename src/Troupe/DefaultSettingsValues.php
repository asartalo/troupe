<?php

namespace Troupe;

class DefaultSettingsValues {
  
  private $project_dir;

  function __construct($project_dir) {
    $this->project_dir = $project_dir;
  }

  function getValues() {
    $user_info = posix_getpwuid(posix_getuid());
    return array(
      'data_dir'    => $user_info['dir'] . '/.troupe/data',
      'vendor_dir'  => 'vendor',
      'project_dir' => $this->project_dir,
      'test_dir'    => realpath(__DIR__ . '/../../') . '/tests'
    );
  }

}
