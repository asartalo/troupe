<?php

namespace Troupe;

use \Troupe\Importer;
use \Troupe\SystemUtilities;

class ManagerFactory {

  function getManager() {
    return new Manager(
      $project_root_dir, $dependencies, $importer, $system_utilities
    );
  }

}
