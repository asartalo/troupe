<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Reader;
use \Troupe\Utilities;

class ReaderTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->system_utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->project_directory = 'a/directory';
    $this->file = $this->quickMock('Troupe\File\Php');
    $this->file->expects($this->any())
      ->method('getPath')
      ->will($this->returnValue('a/directory/mytroupe.php'));
    $this->reader = new Reader(
      $this->file, $this->system_utilities
    );
  }
  
  private function fileExistsReturns($bool) {
    $this->file->expects($this->once())
      ->method('isFileExists')
      ->will($this->returnValue($bool));
  }
  
  private function includesFileWith($file) {
    return $this->system_utilities->expects($this->once())
      ->method('fileExists')->with($file);
  }
  
  function testGetDependencyListChecksIfAssemblyFileExistsInProjectDirectory() {
    $this->file->expects($this->once())
      ->method('isFileExists');
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
