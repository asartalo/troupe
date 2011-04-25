<?php

namespace Troupe\Tests\Functional;

require_once realpath(__DIR__ . '/../../../bootstrap.php');
require_once realpath(__DIR__ . '/../ArgvParser.php');
require_once realpath(__DIR__ . '/../CheckOutput.php');
require_once realpath(__DIR__ . '/../RobotSource.php');

use Troupe\Container;
use Troupe\Tests\RobotSource;

class TestContainer extends Container {
  
  function dependencyContainerSetup($dc) {
    $dc->Source = function(\Pimple $c) {
      return \Troupe\Tests\RobotSource::getInstance($c->options['url']);
    };
  }
}

class StandardUseCaseTest extends \Troupe\Tests\TestCase {
  
  function setUp() {
    $this->project_dir = $this->getTestDataDir() . '/test_project';
    $this->argv_parser = new \Troupe\Tests\ArgvParser('foo.php');
    $this->output = new \Troupe\Tests\CheckOutput;
    $this->container = new TestContainer(
      $this->project_dir, array()
    );
    $this->clearTestDataDir();
    $this->setupProjectDir();
  }
  
  private function setupProjectDir() {
    $this->recursive_copy(
      $this->getFixturesDir() . '/test_project',
      $this->getTestDataDir() . '/test_project'
    );
  }
  
  private function recursive_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
      if (( $file != '.' ) && ( $file != '..' )) { 
        if ( is_dir($src . '/' . $file) ) { 
          $this->recursive_copy($src . '/' . $file,$dst . '/' . $file); 
        } 
        else { 
          copy($src . '/' . $file,$dst . '/' . $file); 
        } 
      } 
    } 
    closedir($dir); 
  } 
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  function testBasicIntegration() {
    $this->container->EnvironmentHelper->run();
  }
  
  function testListDependencies() {
    $commands = $this->argv_parser->parse('list');
    $this->container = new TestContainer(
      $this->project_dir, $commands
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
  
  function testAssemble() {
    $commands = $this->argv_parser->parse('assemble');
    $this->container = new TestContainer(
      $this->project_dir, $commands
    );
    $this->container->Output = $this->output;
    RobotSource::setImportSuccessStatus(
      'git://some.git.site/iko/iko.git'
    );
    $iko = RobotSource::getInstance('git://some.git.site/iko/iko.git');
    RobotSource::setImportSuccessStatus(
      'http://svn.barsite.com/blon_repo'
    );
    $this->container->EnvironmentHelper->run();
    $this->assertContains(
      "Importing: iko\n" .
      "SUCCESS: Robot says 'git://some.git.site/iko/iko.git' import is successful.",
      $this->output->getOutput(),
      $this->output->getOutput()
    );
    $this->assertContains(
      "Importing: blon\n" .
      "SUCCESS: Robot says 'http://svn.barsite.com/blon_repo' import is successful.",
      $this->output->getOutput(),
      $this->output->getOutput()
    );
    $this->assertContains(
      "Importing: pcell\n" .
      "FAIL: Robot says 'http://www.somewhere.org/files/pcell.zip' import failed.",
      $this->output->getOutput(),
      $this->output->getOutput()
    );
  }
  
  function testUpdatingTroupe() {
    $commands = $this->argv_parser->parse('update');
    $this->container = new TestContainer(
      $this->project_dir, $commands
    );
    $this->container->Output = $this->output;
    RobotSource::setUpdateSuccessStatus(
      'git://some.git.site/iko/iko.git'
    );
    RobotSource::setUpdateSuccessStatus(
      'http://svn.barsite.com/blon_repo'
    );
    RobotSource::setUpdateFailureStatus(
      'http://www.somewhere.org/files/pcell.zip'
    );
    $this->container->EnvironmentHelper->run();
    $this->assertContains(
      "Updating: iko\n" .
      "SUCCESS: Robot says 'git://some.git.site/iko/iko.git' update is successful.",
      $this->output->getOutput(),
      $this->output->getOutput()
    );
    $this->assertContains(
      "Updating: blon\n" .
      "SUCCESS: Robot says 'http://svn.barsite.com/blon_repo' update is successful.",
      $this->output->getOutput(),
      $this->output->getOutput()
    );
    $this->assertContains(
      "Updating: pcell\n" .
      "FAIL: Robot says 'http://www.somewhere.org/files/pcell.zip' update failed.",
      $this->output->getOutput(),
      $this->output->getOutput()
    );
  }
  
}
