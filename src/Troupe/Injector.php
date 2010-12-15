<?php

namespace Troupe;

// TODO: Store object caches in Scope object not in Injector
class Injector {
  
  private static
    $system_utilities,
    $utilities;

  public static function injectEnvironmentHelper(EnvironmentScope $scope) {
    return new EnvironmentHelper(
      self::injectCliController($scope),
      $scope->getSystemEnvVariables(),
      $scope->getCwd(),
      $scope->getArgs(),
      self::injectTaskLists($scope)
    );
  }
  
  public static function injectTaskLists(EnvironmentScope $scope) {
    return array(
      self::injectTroupeTaskList($scope)
    );
  }
  
  public static function injectTroupeTaskList(EnvironmentScope $scope) {
    return new Cli\TroupeTasks(
      self::injectSystemUtilities($scope),
      self::injectManager($scope)
    );
  }
  
  public static function injectManager(EnvironmentScope $scope) {
    return new Manager(
      self::injectProjectRootDirectory($scope),
      self::injectDependencies($scope),
      self::injectImporter($scope),
      self::injectSystemUtilities($scope),
      self::injectVendorDirectoryManager($scope)
    );
  }
  
  public static function injectProjectRootDirectory(EnvironmentScope $scope) {
    return $scope->getCwd();
  }
  
  public static function injectDependencies(EnvironmentScope $scope) {
    return self::injectDependencyFactory($scope)
      ->getDependencies(self::injectTroupeList($scope));
  }
  
  public static function injectDependencyFactory(EnvironmentScope $scope) {
    return new Dependency\Factory(
      self::injectSourceFactory($scope),
      self::injectProjectRootDirectory($scope),
      self::injectSettings($scope)
    );
  }
  
  public static function injectTroupeList(EnvironmentScope $scope) {
    return self::injectReader($scope)->getDependencyList();
  }

  public static function injectSettings(EnvironmentScope $scope) {
    return new Settings(
      self::injectRawSettingsData($scope)
    );
  }
  
  public static function injectRawSettingsData(EnvironmentScope $scope) {
    return self::injectReader($scope)->getSettings();
  }
  
  public static function injectReader(EnvironmentScope $scope) {
    return new Reader(
      self::injectProjectRootDirectory($scope),
      self::injectSystemUtilities($scope)
    );
  }
  
  public static function injectSourceFactory(EnvironmentScope $scope) {
    return new Source\Factory(
      self::injectSystemUtilities($scope)
    );
  }
  
  public static function injectImporter(EnvironmentScope $scope) {
    return new Importer(
      self::injectVendorDirectoryManager($scope),
      self::injectSystemUtilities($scope)
    );
  }
  
  public static function injectVendorDirectoryManager(EnvironmentScope $scope) {
    return new VendorDirectoryManager(
      self::injectSystemUtilities($scope),
      self::injectSettings($scope)
    );
  }
  
  public static function injectSystemUtilities(EnvironmentScope $scope) {
    if (!self::$system_utilities) {
      self::$system_utilities = new SystemUtilities;
    }
    return self::$system_utilities;
  }
  
  public static function injectUtilities(EnvironmentScope $scope) {
    if (!self::$utilities) {
      self::$utilities = new Utilities;
    }
    return self::$utilities;
  }
  
  public static function injectCliController(EnvironmentScope $scope) {
    return new Cli\Controller(
      self::injectCliInterpreter($scope),
      self::injectCliExecutor($scope),
      $scope->getCwd()
    );
  }
  
  public static function injectCliInterpreter(EnvironmentScope $scope) {
    return new Cli\Interpreter;
  }
  
  public static function injectCliExecutor(EnvironmentScope $scope) {
    return new Cli\Executor(
      self::injectUtilities($scope)
    );
  }
  
}
