<?php

namespace Troupe\Tests\Functional;
use Troupe\Container;

require_once realpath(__DIR__ . '/../../../bootstrap.php');
require_once realpath(__DIR__ . '/../ArgvParser.php');
require_once realpath(__DIR__ . '/../CheckOutput.php');

class StandardUseCaseTest extends \Troupe\Tests\TestCase {
  
  function setUp() {
    $this->data_dir = realpath(__DIR__ . '/../../../../data');
    $this->project_dir = realpath(__DIR__ . '/../../../fixtures/test_project');
    $this->argv_parser = new \Troupe\Tests\ArgvParser('foo.php');
    $this->output = new \Troupe\Tests\CheckOutput;
    $this->container = new Container(
      array(), $this->project_dir, array()
    );
  }
  
  function testBasicIntegration() {
    $this->container->EnvironmentHelper->run();
  }
  
  function testListDependencies() {
    $commands = $this->argv_parser->parse('list');
    $this->container = new Container(
      array(), $this->project_dir, $commands
    );
    $this->container->Output = $this->output;
    $this->container->EnvironmentHelper->run();
    $this->assertContains(
      "iko : git://some.git.site/iko/iko.git\n" .
        "blon : http://svn.barsite.com/blon_repo\n" .
        "pcell : http://www.somewhere.org/files/pcell.zip\n" .
        "planter (seu) : http://tohaheavyindustries.city/files/planter.tar.gz\n" .
        "sanakan : http://somewhere.net/sanakan.tgz\n",
      $this->output->getOutput(),
      $this->output->getOutput()
    );
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
