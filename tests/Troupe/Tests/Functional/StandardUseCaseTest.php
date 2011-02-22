<?php

namespace Troupe\Tests\Functional;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

class StandardUseCaseTest extends \PHPUnit_Framework_TestCase {
  
  function setUp() {
    $this->data_dir = realpath(__DIR__ . '/../../../../data');
  }
  
  function testBasicIntegration() {
    $args = array();
		$scope = new \Troupe\EnvironmentScope(
		  array(), getcwd(), $this->data_dir, $args
		);
		\Troupe\Injector::injectEnvironmentHelper($scope)->run();
  }
  
  function testSettingGlobalSettings() {
    $this->markTestIncomplete();
  }
  
}
