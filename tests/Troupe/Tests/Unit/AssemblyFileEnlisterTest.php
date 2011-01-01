<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\AssemblyFileEnlister;

class AssemblyFileEnlisterTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->clearTestDataDir();
    $this->project_dir = $this->getTestDataDir();
    $this->enlister = new AssemblyFileEnlister($this->project_dir);
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  function testGettingNoAssemblyFile() {
    $this->assertEquals('', $this->enlister->getAssemblyFile());
  }
  
  function testGettingPhpAssemblyFile() {
    $this->createTestFile('mytroupe.php', 'Php');
    $this->assertEquals(
      $this->project_dir . '/mytroupe.php', $this->enlister->getAssemblyFile()
    );
  }
  
  function testGettingIniAssemblyFile() {
    $this->createTestFile('mytroupe.ini', 'Ini');
    $this->assertEquals(
      $this->project_dir . '/mytroupe.ini', $this->enlister->getAssemblyFile()
    );
  }
  
}
