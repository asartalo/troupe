<?php

namespace Troupe\Dependency;

use \Troupe\Source\Factory as SourceFactory;

class Factory {
  
  private $source_factory, $project_dir;
  
  function __construct(SourceFactory $source_factory, $project_dir) {
    $this->source_factory = $source_factory;
    $this->project_dir = $project_dir;
  }
  
  function getDependencies(array $troupe_list) {
    $dependencies = array();
    foreach ($troupe_list as $name => $options) {
      $source = $this->source_factory->get($options['url'], $options['type']);
      $dependencies[] = new Dependency($name, $source,
        $this->project_dir . DIRECTORY_SEPARATOR . 'vendor'
      );
    }
    return $dependencies;
  }
  
}
