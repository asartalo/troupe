<?php

namespace Troupe\Tests\Functional;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

class StandardUseCaseTest extends \PHPUnit_Framework_TestCase {
  
  function setUp() {
    
  }
  
  function testBasicIntegration() {
    $data_dir = realpath(__DIR__ . '/../../../../data');
		$scope = new \Troupe\EnvironmentScope(
		  array(), getcwd(), $data_dir, array()
		);
		\Troupe\Injector::injectEnvironmentHelper($scope)->run();
  }
  
}
