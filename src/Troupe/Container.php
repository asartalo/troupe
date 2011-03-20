<?php

namespace Troupe;

class Container extends \Pimple {
  
  function __construct($env, $cwd, $data_dir, $args) {
    $this->env = $env;
    $this->cwd = $cwd;
    $this->data_dir = $data_dir;
    $this->args = $args;
    $this->defineGraph();
  }
  
  private function defineGraph() {
    $this->EnvironmentHelper = function(\Pimple $c) {
      return new EnvironmentHelper(
        $c->CliController,
        $c->env,
        $c->cwd,
        $c->args,
        $c->TaskLists
      );
    };

    $this->TaskLists = function(\Pimple $c) {
      return array($c->TroupeTaskList);
    };

    $this->TroupeTaskList = function(\Pimple $c) {
      return new Cli\TroupeTasks(
        $c->SystemUtilities, $c->Manager
      );
    };

    $this->Manager = function(\Pimple $c) {
      return new Manager(
        $c->ProjectRootDirectory,
        $c->Dependencies,
        $c->Importer,
        $c->SystemUtilities,
        $c->VendorDirectoryManager,
        $c->Logger
      );
    };

    $this->Logger = function(\Pimple $c) {
      return new Logger;
    };

    $this->ProjectRootDirectory = function(\Pimple $c) {
      return $c->cwd;
    };

    $this->Dependencies = function(\Pimple $c) {
      return $c->DependencyFactory
        ->getDependencies($c->TroupeList);
    };

    $this->DependencyFactory = function(\Pimple $c) {
      return new Dependency\Factory(
        $c->SourceFactory, $c->ProjectRootDirectory, $c->Settings
      );
    };

    $this->TroupeList = function(\Pimple $c) {
      return $c->Reader->getDependencyList();
    };

    $this->Settings = function(\Pimple $c) {
      return new Settings($c->RawSettingsData);
    };

    $this->RawSettingsData = function(\Pimple $c) {
      return $c->Reader->getSettings();
    };

    $this->Reader = function(\Pimple $c) {
      return $c->ReaderFactory->getReader($c->AssemblyFile);
    };

    $this->ReaderFactory = function(\Pimple $c) {
      return new Reader\Factory($c->SystemUtilities);
    };

    $this->AssemblyFile = function(\Pimple $c) {
      return $c->FileFactory->getFile($c->AssemblyFileEnlisted);
    };

    $this->FileFactory = function(\Pimple $c) {
      return new File\Factory();
    };

    $this->AssemblyFileEnlisted = function(\Pimple $c) {
      return $c->AssemblyFileEnlister->getAssemblyFile();
    };

    $this->AssemblyFileEnlister = function(\Pimple $c) {
      return new AssemblyFileEnlister($c->ProjectRootDirectory);
    };

    $this->SourceFactory = function(\Pimple $c) {
      return new Source\Factory(
        $c->SystemUtilities, $c->VendorDirectoryManager, $c->ExpanderFactory
      );
    };

    $this->ExpanderFactory = function(\Pimple $c) {
      return new Expander\Factory($c->Utilities);
    };

    $this->Importer = function(\Pimple $c) {
      return new Importer($c->VendorDirectoryManager, $c->SystemUtilities);
    };

    $this->VendorDirectoryManager = function(\Pimple $c) {
      return new VendorDirectoryManager(
        $c->SystemUtilities, $c->DataStore, $c->Settings
      );
    };

    $this->DataStore = $this->asShared(function(\Pimple $c) {
      return new DataStore($c->data_dir);
    });

    $this->SystemUtilities = $this->asShared(function(\Pimple $c) {
      return new SystemUtilities;
    });

    $this->Utilities = $this->asShared(function(\Pimple $c) {
      return new Utilities;
    });

    $this->CliController = function(\Pimple $c) {
      return new Cli\Controller(
        $c->CliInterpreter, $c->CliExecutor, $c->cwd
      );
    };

    $this->CliInterpreter = function(\Pimple $c) {
      return new Cli\Interpreter;
    };

    $this->CliExecutor = function(\Pimple $c) {
      return new Cli\Executor($c->Utilities);
    };

    $this->FileWriter = function(\Pimple $c) {
      return new FileWriter;
    };
  }

}
