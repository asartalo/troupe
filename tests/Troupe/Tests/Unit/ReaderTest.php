<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Reader;
use \Troupe\Utilities;

class ReaderTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $this->system_utilities = $this->getMock('Troupe\SystemUtilities');
    $this->project_directory = 'a/directory';
    $this->reader = new Reader(
      $this->project_directory, $this->system_utilities
    );
  }
  
  private function fileExistsReturns($bool) {
    $this->system_utilities->expects($this->once())
      ->method('fileExists')
      ->will($this->returnValue($bool));
  }
  
  private function includesFileWith($file) {
    return $this->system_utilities->expects($this->once())
      ->method('fileExists')->with($file);
  }
  
  function testGetDependencyListChecksIfAssemblyFileExistsInProjectDirectory() {
    $this->includesFileWith('a/directory/mytroupe.php');
    $this->reader->getDependencyList();
  }
  
  function testGetDependencyListIncludesAndReturnsValueOfAssemblyFile() {
    $foo = array('foo' => array(), 'bar' => array(), 'baz' => array());
    $this->fileExistsReturns(true);
    $this->system_utilities->expects($this->once())
      ->method('includeFile')
      ->with('a/directory/mytroupe.php')
      ->will($this->returnValue($foo));
    $this->assertEquals($foo, $this->reader->getDependencyList());
  }
  
  function testGetDependencyListSkipsSettings() {
    $foo = array('foo' => array(), '_settings' => array(), 'baz' => array());
    $this->fileExistsReturns(true);
    $this->system_utilities->expects($this->once())
      ->method('includeFile')
      ->will($this->returnValue($foo));
    $foo_0 = $foo;
    unset($foo_0['_settings']);
    $this->assertEquals($foo_0, $this->reader->getDependencyList());
  }
  
  function testGetSettings() {
    $foo = array('foo' => array(), '_settings' => array('bar' => 2));
    $this->fileExistsReturns(true);
    $this->system_utilities->expects($this->once())
      ->method('includeFile')
      ->will($this->returnValue($foo));
    $this->assertEquals($foo['_settings'], $this->reader->getSettings());
  }
  
  function testGetSettingsReturnsEmptyArrayWhenSettingsIsNotFound() {
    $foo = array('foo' => array());
    $this->fileExistsReturns(true);
    $this->system_utilities->expects($this->once())
      ->method('includeFile')
      ->will($this->returnValue($foo));
    $this->assertEquals(array(), $this->reader->getSettings());
  }
  
  function testGetDependencyListReturnsEmptyArrayWhenAssemblyFileIsNotFound() {
    $this->fileExistsReturns(false);
    $this->assertEquals(array(), $this->reader->getDependencyList());
  }
  
  function testGetDependencyListSkipsIncludesIfAssemblyFileIsNotFound() {
    $this->fileExistsReturns(false);
    $this->system_utilities->expects($this->never())
      ->method('includeFile');
    $this->reader->getDependencyList();
  }
  
}
