<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Git;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;


class GitTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->executor  = $this->getMock('Troupe\Executor');
    $this->url = 'http://git.source.com/example';
    $this->data_dir = 'a/path/to/a/directory';
    $this->vdm = $this->quickMock('Troupe\VendorDirectory\Manager', array('isDataImported', 'importSuccess'));
    $this->git_source = new Git($this->url, $this->vdm, $this->executor, $this->data_dir);
  }
  
  function testImportChecksWithVdmIfDataHasAlreadyBeenImported() {
    $this->vdm->expects($this->once())
      ->method('isDataImported')
      ->with($this->url);
    $this->git_source->import();
  }
  
  private function vdmIsDataImported($bool) {
    return $this->vdm->expects($this->once())
      ->method('isDataImported')
      ->will($this->returnValue($bool));
  }

  function testImport() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(false);
    $this->executor->expects($this->once())
      ->method('system')
      ->with("git clone --recursive '{$this->url}' '{$this->data_dir}/$folder_name'");
    $this->git_source->import();
  }
  
  function testImportSkipsCheckoutIfDataHasAlreadyBeenImported() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(true);
    $this->executor->expects($this->never())
      ->method('system');
    $this->git_source->import();
  }
  
  function testImportReturnsOkayStatusMessageIfSuccessful() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(false);
    $status = new Success(
      \Troupe\Source\STATUS_OK, "SUCCESS: Imported {$this->url}.",
      "{$this->data_dir}/$folder_name"
    );
    $this->checkoutIsSuccessful();
    $this->assertEquals($status, $this->git_source->import());
  }
  
  function checkOutIsSuccessful() {
    $this->executor->expects($this->once())
      ->method('system')
      ->will(
        $this->returnValue('Initialized empty Git repository in foo/bar.')
      );
  }
  
  function checkOutIsSuccessfulForGitRepositoriesWithSubmodules() {
    $this->executor->expects($this->once())
      ->method('system')
      ->will(
        $this->returnValue(
          "Submodule path 'submodules/baz': checked out " .
          "'a46c6180f96647fdd66e2c8f2771d61ecebe6a3f'"
        )
      );
  }
  
  function testImportReturnsOkayStatusMessageIfSuccessfulForSubmoduledRepos() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(false);
    $status = new Success(
      \Troupe\Source\STATUS_OK, "SUCCESS: Imported {$this->url}.",
      "{$this->data_dir}/$folder_name"
    );
    $this->checkOutIsSuccessfulForGitRepositoriesWithSubmodules();
    $this->assertEquals($status, $this->git_source->import());
  }
  
  function testImportTellsVdmThatTheDataHasBeenImportedSuccessfuly() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(false);
    $this->checkoutIsSuccessful();
    $this->vdm->expects($this->once())
      ->method('importSuccess')
      ->with($this->url);
    $this->git_source->import();
  }
  
  function testImportReturnsOkayStatusMessageIfVdmSaysDataHasAlreadyBeenImported() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(true);
    $status = new Success(
      \Troupe\Source\STATUS_OK, "SUCCESS: {$this->url} has already been imported.",
      "{$this->data_dir}/$folder_name"
    );
    $this->assertEquals($status, $this->git_source->import());
  }
  
  function testImportReturnsFailStatusMessageIfNotSuccessful() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(false);
    $status = new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unable to import {$this->url}."
    );
    $this->executor->expects($this->once())
      ->method('system')
      ->will($this->returnValue('Foo.'));
    $this->assertEquals($status, $this->git_source->import());
  }
  
  function testImportSkipsImportSuccessIfNotSuccessful() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(false);
    $this->executor->expects($this->once())
      ->method('system')
      ->will($this->returnValue('Foo.'));
    $this->vdm->expects($this->never())
      ->method('importSuccess');
    $this->git_source->import();
  }

}
