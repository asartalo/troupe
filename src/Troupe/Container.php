<?php

namespace Troupe;

class Container extends \Pimple {

  function __construct($project_root_dir, $args) {
    $this['project_root_dir'] = $project_root_dir;
    $this['args'] = $args;
    $this->defineGraph();
  }

  function dependencyContainerEntrance(
    $name, $project_root_dir, $settings, $options, $vdm, $executor, $system_utilities,
    $data_directory
  ) {
    $dependency_container = new Dependency\Container(
      $name, $project_root_dir, $settings, $options,
      $vdm, $executor, $system_utilities,
      $data_directory
    );
    $this->dependencyContainerSetup($dependency_container);
    return $dependency_container;
  }

  function dependencyContainerSetup($dependency_container) {}

  private function defineGraph() {
    $this['EnvironmentHelper'] = function(\Pimple $c) {
      return new EnvironmentHelper(
        $c['CliController'],
        $c['args'],
        $c['TaskLists']
      );
    };

    $this['TaskLists'] = function(\Pimple $c) {
      return array($c['TroupeTaskList']);
    };

    $this['TroupeTaskList'] = function(\Pimple $c) {
      return new Cli\TroupeTasks(
        $c['Output'], $c['Manager']
      );
    };

    $this['Manager'] = function(\Pimple $c) {
      return new Manager(
        $c['ProjectRootDirectory'],
        $c['Dependencies'],
        $c['Importer'],
        $c['Output'],
        $c['VendorDirectoryManager'],
        $c['Logger']
      );
    };

    $this['Output'] = $this->share(function (\Pimple $c) {
      return new Output;
    });

    $this['Logger'] = function(\Pimple $c) {
      return new Logger;
    };

    $this['ProjectRootDirectory'] = function(\Pimple $c) {
      return $c['project_root_dir'];
    };

    $this['Dependencies'] = function(\Pimple $c) {
      $dependencies = array();
      // Enter scope!!!
      foreach ($c['TroupeList'] as $name => $options) {
        $d = $c->dependencyContainerEntrance(
          $name, $c['project_root_dir'], $c['Settings'], $options,
          $c['VendorDirectoryManager'], $c['Executor'], $c['SystemUtilities'],
          $c['data_directory']
        );
        $dependencies[] = $d['Dependency'];
      }
      return $dependencies;
    };

    $this['data_directory'] = function(\Pimple $c) {
      return $c['Settings']->get('data_dir');
    };

    $this['TroupeList'] = function(\Pimple $c) {
      return $c['Reader']->getDependencyList();
    };

    $this['Settings'] = $this->share(function(\Pimple $c) {
      return new Settings(
        array_merge($c['DefaultSettings'], $c['RawSettingsData'])
      );
    });

    $this['DefaultSettingsValues'] = function (\Pimple $c) {
      return new DefaultSettingsValues($c['ProjectRootDirectory']);
    };

    $this['DefaultSettings'] = function (\Pimple $c) {
      return $c['DefaultSettingsValues']->getValues();
    };

    $this['RawSettingsData'] = function(\Pimple $c) {
      return $c['Reader']->getSettings();
    };

    $this['Reader'] = function(\Pimple $c) {
      return $c['ReaderFactory']->getReader($c['AssemblyFile']);
    };

    $this['ReaderFactory'] = function(\Pimple $c) {
      return new Reader\Factory($c['SystemUtilities']);
    };

    $this['AssemblyFile'] = function(\Pimple $c) {
      return $c['FileFactory']->getFile($c['AssemblyFileEnlisted']);
    };

    $this['FileFactory'] = function(\Pimple $c) {
      return new File\Factory();
    };

    $this['AssemblyFileEnlisted'] = function(\Pimple $c) {
      return $c['AssemblyFileEnlister']->getAssemblyFile();
    };

    $this['AssemblyFileEnlister'] = function(\Pimple $c) {
      return new AssemblyFileEnlister($c['ProjectRootDirectory']);
    };

    $this['Executor'] = function(\Pimple $c) {
      return new Executor;
    };

    $this['ExpanderFactory'] = function(\Pimple $c) {
      return new Expander\Factory($c['Utilities']);
    };

    $this['Importer'] = function(\Pimple $c) {
      return new Importer($c['VendorDirectoryManager'], $c['Output']);
    };

    $this['VendorDirectoryManager'] = function(\Pimple $c) {
      return new VendorDirectory\Manager(
        $c['SystemUtilities'], $c['DataStore'], $c['Settings']
      );
    };

    $this['DataStore'] = $this->share(function(\Pimple $c) {
      return new DataStore($c['data_dir']);
    });

    $this['data_dir'] = function (\Pimple $c) {
      return $c['Settings']->get('data_dir');
    };

    $this['SystemUtilities'] = $this->share(function(\Pimple $c) {
      return new SystemUtilities;
    });

    $this['Utilities'] = $this->share(function(\Pimple $c) {
      return new Utilities;
    });

    $this['CliController'] = function(\Pimple $c) {
      return \Silly\Silly::getController($c['TroupeTaskList'], $c['project_root_dir']);
    };

    /*
    $this['CliInterpreter'] = function(\Pimple $c) {
      return new Cli\Interpreter;
    };

    $this['CliExecutor'] = function(\Pimple $c) {
      return new Cli\Executor($c['Utilities']);
    };*/

    $this['FileWriter'] = function(\Pimple $c) {
      return new FileWriter;
    };

  }

}
