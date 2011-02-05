<?php
namespace Troupe\Tests\Unit\Reader;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Reader\Ini;
use \Troupe\Utilities;

class IniTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->system_utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->file = $this->quickMock('Troupe\File\Ini');
    $this->file->expects($this->any())
      ->method('getPath')
      ->will($this->returnValue('a/directory/mytroupe.php'));
    $this->reader = new Ini(
      $this->file, $this->system_utilities
    );
  }
  
  private function fileExistsReturns($bool) {
    $this->file->expects($this->any())
      ->method('isFileExists')
      ->will($this->returnValue($bool));
  }
  
  private function includesFileWith($file) {
    return $this->system_utilities->expects($this->once())
      ->method('fileExists')->with($file);
  }
  
  function testGetDependencyListParsesAssemblyFileAndReturnsValue() {
    $contents = 
      "[one]\n\n" .
      "[two]\n\n" .
      "[three]\n\n";
    $this->fileExistsReturns(true);
    $this->file->expects($this->once())
      ->method('getContents')
      ->will($this->returnValue($contents));
    $this->assertEquals(
      array(
        'one' => array(),
        'two' => array(),
        'three' => array()
      ),
      $this->reader->getDependencyList()
    );
  }
  
  function testGetDependencyListSkipsSettings() {
    $contents = 
      "[foo]\n\n" .
      "[_settings]\n\n" .
      "[baz]\n\n";
    $expected = array('foo' => array(), 'baz' => array());
    $this->fileExistsReturns(true);
    $this->file->expects($this->once())
      ->method('getContents')
      ->will($this->returnValue($contents));
    $this->assertEquals($expected, $this->reader->getDependencyList());
  }
  
  function testGetSettings() {
    $contents = 
      "[foo]\n\n" .
      "[_settings]\nbar = 2\n\n" .
      "[baz]\n\n";
    $expected = array('bar' => 2);
    $this->fileExistsReturns(true);
    $this->file->expects($this->once())
      ->method('getContents')
      ->will($this->returnValue($contents));
    $this->assertEquals($expected, $this->reader->getSettings());
  }
  
  function testGetSettingsReturnsEmptyArrayWhenSettingsIsNotFound() {
    $contents = 
      "[foo]\n\n" .
      "[baz]\n\n";
    $this->fileExistsReturns(true);
    $this->file->expects($this->once())
      ->method('getContents')
      ->will($this->returnValue($contents));
    $this->assertEquals(array(), $this->reader->getSettings());
  }
  
  function testGetSettingsReturnsEmptyArrayWhenFileDoesNotExist() {
    $this->fileExistsReturns(false);
    $this->assertEquals(array(), $this->reader->getSettings());
  }
  
  function testGetDependencyListReturnsEmptyArrayWhenAssemblyFileIsNotFound() {
    $this->fileExistsReturns(false);
    $this->assertEquals(array(), $this->reader->getDependencyList());
  }
  
}
