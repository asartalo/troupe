<?php

namespace Troupe\Tests\Functional;
use Troupe\Container;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

class StandardUseCaseTest extends \PHPUnit_Framework_TestCase {
  
  function setUp() {
    $this->data_dir = realpath(__DIR__ . '/../../../../data');
    $this->project_dir = realpath(__DIR__ . '/../../../fixtures/test_project');
    $this->container = new Container(
      array(), $this->project_dir, array()
    );
  }
  
  function testBasicIntegration() {
    $this->container->EnvironmentHelper->run();
  }
  
  function testGettingDependencies() {
    $dependencies = $this->container->Manager->getDependencies();
    $this->assertInternalType('array', $dependencies);
    $this->assertEquals(5, count($dependencies));
  }
  
  function testSettings() {
    $this->assertEquals('src', $this->container->Settings->get('vendor_dir'));
  }
  
  function testGettingFullDirectoryFromManager() {
    $this->assertEquals(
      $this->project_dir . '/src',
      $this->container->Manager->getVendorDirectory()
    );
  }
  
}
