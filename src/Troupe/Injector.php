<?php

namespace Troupe;

class Injector {

  public static function injectEnvironmentHelper(EnvironmentScope $scope) {
    return new EnvironmentHelper(
      self::injectCli($scope),
      $scope->getSystemEnvVariables(),
      $scope->getCwd(),
      $scope->getArgs(),
      self::injectTaskLists($scope)
    );
  }
  
  public static function injectTaskLists(EnvironmentScope $scope) {
    return array(
      
    );
  }
  
  public static function injectCli(EnvironmentScope $scope) {
    return new Cli(
      self::injectCliInterpreter($scope),
      self::injectCliExecutor($scope),
      $scope->getCwd()
    );
  }
  
}
