<?php

namespace Troupe\Tests\Functional;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

class StandardUseCaseTest extends \PHPUnit_Framework_TestCase {
  
  function setUp() {
    $this->data_dir = realpath(__DIR__ . '/../../../../data');
  }
  
  function testBasicIntegration() {
    $args = array();
		$container = new \Troupe\Container(
      array(), getcwd(), $args
    );
    $container->EnvironmentHelper->run();
  }
  
  function testSettingGlobalSettings() {
    $this->markTestIncomplete();
  }
  
}
