<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Svn;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;


class SvnTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $this->utilities  = $this->getMock('Troupe\SystemUtilities');
    $this->url = 'http://svn.source.com/example';
    $this->data_dir = 'a/path/to/a/directory';
    $this->svn_source = new Svn($this->url, $this->utilities, $this->data_dir);
  }

  function testImport() {
    $folder_name = md5($this->url);
    $this->utilities->expects($this->once())
      ->method('system')
      ->with("svn co '{$this->url}' '{$this->data_dir}/$folder_name'");
    $this->svn_source->import();
  }
  
  function testImportReturnsOkayStatusMessageIfSuccessful() {
    $folder_name = md5($this->url);
    $status = new Success(
      \Troupe\Source\STATUS_OK, "SUCCESS: Imported {$this->url}.",
      "{$this->data_dir}/$folder_name"
    );
    $this->utilities->expects($this->once())
      ->method('system')
      ->will($this->returnValue('Checked out revision 3.'));
    $this->assertEquals($status, $this->svn_source->import());
  }
  
  function testImportReturnsFailStatusMessageIfNotSuccessful() {
    $folder_name = md5($this->url);
    $status = new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unable to import {$this->url}."
    );
    $this->utilities->expects($this->once())
      ->method('system')
      ->will($this->returnValue('Foo.'));
    $this->assertEquals($status, $this->svn_source->import());
  }
  
  function testImportChecksIfFolderExists() {
    $folder_name = md5($this->url);
    $this->utilities->expects($this->once())
      ->method('fileExists')
      ->with("{$this->data_dir}/$folder_name");
    $this->svn_source->import();
  }
  
  function testImportUpdatesSourceWhenFolderExists() {
    $folder_name = md5($this->url);
    $this->utilities->expects($this->once())
      ->method('fileExists')
      ->will($this->returnValue(true));
    $this->utilities->expects($this->once())
      ->method('system')
      ->with("svn update '{$this->data_dir}/$folder_name'");
    $this->svn_source->import();
  }
  
  function testImportReturnsStatusOkUpdateWhenFolderExists() {
    $folder_name = md5($this->url);
    $this->utilities->expects($this->once())
      ->method('fileExists')
      ->will($this->returnValue(true));
    $this->utilities->expects($this->once())
      ->method('system')
      ->will($this->returnValue('At revision 9.'));
    $status = new Success(
      \Troupe\Source\STATUS_OK_UPDATE, "SUCCESS: Updated {$this->url}.",
      "{$this->data_dir}/$folder_name"
    );
    $this->assertEquals($status, $this->svn_source->import());
  }
  
  function testImportReturnsStatusFailWhenUpdateFails() {
    $folder_name = md5($this->url);
    $this->utilities->expects($this->once())
      ->method('fileExists')
      ->will($this->returnValue(true));
    $this->utilities->expects($this->once())
      ->method('system')
      ->will($this->returnValue('Bar'));
    $status = new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unable to import {$this->url}."
    );
    $this->assertEquals($status, $this->svn_source->import());
  }

}