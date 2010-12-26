<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Svn;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;


class SvnTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->utilities  = $this->getMock('Troupe\SystemUtilities');
    $this->url = 'http://svn.source.com/example';
    $this->data_dir = 'a/path/to/a/directory';
    $this->vdm = $this->quickMock('Troupe\VendorDirectoryManager');
    $this->svn_source = new Svn($this->url, $this->vdm, $this->utilities, $this->data_dir);
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

}
