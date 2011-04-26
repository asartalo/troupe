<?php

namespace Troupe\Dependency;

use \Troupe\Settings;
use \Troupe\VendorDirectory\Manager as VDM;
use \Troupe\Executor;
use \Troupe\SystemUtilities;

class Container extends \Pimple {

  private 
    $_default_options = array(
      'type' => 'unknown'
    ),
    $_source_types = array(
      'svn'     => 'SourceSvn',
      'git'     => 'SourceGit',
      'archive' => 'SourceArchive',
      'file'    => 'SourceFile'
    );
  
  
  function __construct(
    $name, $project_dir, Settings $settings, array $options,
    VDM $vdm, Executor $executor, SystemUtilities $system_utilities,
    $data_directory
  ) {
    $this->name = $name;
    $this->settings = $settings;
    $this->options = array_merge($this->_default_options, $options);
    $this->project_dir = $project_dir;
    $this->VDM = $vdm;
    $this->Executor = $executor;
    $this->SystemUtilities = $system_utilities;
    // TODO: Move this to settings
    $this->data_directory = $data_directory;
    $this->source_types = $this->_source_types;
    $this->defineGraph();
  }
  
  private function defineGraph() {
    
    $this->Dependency = function(\Pimple $c) {
      return new Dependency($c->name, $c->Source, $c->local_dir, $c->alias);
    };
    
    $this->Source = function(\Pimple $c) {
      if (isset($c->source_types[$c->options['type']])) {
        $type = $c->source_types[$c->options['type']];
        return $c->$type;
      }
      return new \Troupe\Source\Unknown;
    };
    
    $this->SourceSvn = function(\Pimple $c) {
      return new \Troupe\Source\Svn(
        $c->options['url'], $c->VDM, $c->Executor, $c->data_directory
      );
    };
    
    $this->SourceGit = function(\Pimple $c) {
      return new \Troupe\Source\Git(
        $c->options['url'], $c->VDM, $c->Executor, $c->data_directory
      );
    };
    
    $this->SourceArchive = function(\Pimple $c) {
      return new \Troupe\Source\Archive(
        $c->options['url'], $c->VDM, $c->SystemUtilities, $c->data_directory,
        $c->Expander, $c->Cibo
      );
    };
    
    $this->SourceFile = function(\Pimple $c) {
      return new \Troupe\Source\File(
        $c->options['url'], $c->VDM, $c->SystemUtilities, $c->data_directory,
        $c->Cibo
      );
    };
    
    $this->Cibo = function(\Pimple $c) {
      return new \Cibo;
    };
    
    $this->Expander = function(\Pimple $c) {
      if (isset($c->options['url'])) {
        $path_info = pathinfo($c->options['url']);
        $ext = strtolower($path_info['extension']);
        $extensions = array(
          'zip' => 'Zip', 'tar' => 'Tar', 'gzip' => 'Gzip',
          'gz' => 'Gzip', 'tgz' => 'Tgz'
        );
        if ($ext == 'gz' && pathinfo($path_info['filename'], PATHINFO_EXTENSION) == 'tar') {
          $ext = 'tgz';
        }
        if (isset($extensions[$ext])) {
          $class = "Expander$extensions[$ext]";
          return $c->$class;
        }
      }
      return new \Troupe\Expander\NullExpander;
    };
    
    $this->ExpanderZip = function (\Pimple $c) {
      return new \Troupe\Expander\Zip;
    };
    
    $this->ExpanderTar = function (\Pimple $c) {
      return new \Troupe\Expander\Tar;
    };
    
    $this->ExpanderGzip = function (\Pimple $c) {
      return new \Troupe\Expander\Gzip;
    };
    
    $this->ExpanderTgz = function (\Pimple $c) {
      return new \Troupe\Expander\Tgz($c->Utilities);
    };
    
    $this->Utilities = function (\Pimple $c) {
      return new \Troupe\Utilities;
    };
    
    $this->local_dir = function(\Pimple $c) {
      return $c->project_dir . '/' . $c->vendor_path;
    };
    
    $this->vendor_path = function(\Pimple $c) {
      return $c->settings->get('vendor_dir');
    };
    
    $this->alias = function(\Pimple $c) {
      return isset($c->options['alias']) ? $c->options['alias'] : '';
    };
  }
  
  
    
}
