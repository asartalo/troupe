<?php

namespace Troupe;

class Injector {

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
      self::injectVendorDirectoryManager($scope),
      self::injectLogger($scope)
    );
  }
  
  public static function injectLogger(EnvironmentScope $scope) {
    return new Logger();
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
      self::injectSystemUtilities($scope),
      self::injectVendorDirectoryManager($scope)
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
      self::injectDataStore($scope),
      self::injectSettings($scope)
    );
  }
  
  public static function injectDataStore(EnvironmentScope $scope) {
    if (!$scope->isInCache('DataStore')) {
      $scope->addToCache('DataStore', new DataStore($scope->getDataDirectory()));
    }
    return $scope->getCache('DataStore');
  }
  
  public static function injectSystemUtilities(EnvironmentScope $scope) {
    if (!$scope->isInCache('SystemUtilities')) {
      $scope->addToCache('SystemUtilities', new SystemUtilities);
    }
    return $scope->getCache('SystemUtilities');
  }
  
  public static function injectUtilities(EnvironmentScope $scope) {
    if (!$scope->isInCache('Utilities')) {
      $scope->addToCache('Utilities', new Utilities);
    }
    return $scope->getCache('Utilities');
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
