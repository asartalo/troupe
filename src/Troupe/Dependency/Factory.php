<?php

namespace Troupe\Dependency;

use \Troupe\Source\Factory as SourceFactory;
use \Troupe\Settings;

class Factory {
  
  private $source_factory, $project_dir, $settings;
  
  function __construct(SourceFactory $source_factory, $project_dir, Settings $settings) {
    $this->source_factory = $source_factory;
    $this->project_dir = $project_dir;
    $this->settings = $settings;
  }
  
  function getDependencies(array $troupe_list) {
    $dependencies = array();
    foreach ($troupe_list as $name => $options) {
      $source = $this->source_factory->get($options['url'], $options['type']);
      $dependencies[] = new Dependency($name, $source,
        $this->project_dir . DIRECTORY_SEPARATOR . 
        $this->settings->get('vendor_dir')
      );
    }
    return $dependencies;
  }
  
}
