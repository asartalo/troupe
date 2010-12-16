<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Git;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;


class GitTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $this->utilities  = $this->getMock('Troupe\SystemUtilities');
    $this->url = 'http://git.source.com/example';
    $this->data_dir = 'a/path/to/a/directory';
    $this->git_source = new Git($this->url, $this->utilities, $this->data_dir);
  }

  function testImport() {
    $folder_name = md5($this->url);
    $this->utilities->expects($this->once())
      ->method('system')
      ->with("git clone '{$this->url}' '{$this->data_dir}/$folder_name'");
    $this->git_source->import();
  }
  
  function testImportReturnsOkayStatusMessageIfSuccessful() {
    $folder_name = md5($this->url);
    $status = new Success(
      \Troupe\Source\STATUS_OK, "SUCCESS: Imported {$this->url}.",
      "{$this->data_dir}/$folder_name"
    );
    $this->utilities->expects($this->once())
      ->method('system')
      ->will($this->returnValue('Initialized empty Git repository in foo/bar.'));
    $this->assertEquals($status, $this->git_source->import());
  }
  
  function testImportReturnsFailStatusMessageIfNotSuccessful() {
    $folder_name = md5($this->url);
    $status = new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unable to import {$this->url}."
    );
    $this->utilities->expects($this->once())
      ->method('system')
      ->will($this->returnValue('Foo.'));
    $this->assertEquals($status, $this->git_source->import());
  }

}
