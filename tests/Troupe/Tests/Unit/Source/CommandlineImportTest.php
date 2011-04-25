<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Status\Success;
use \Troupe\Status\Failure;


class CommandlineImportTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->executor  = $this->getMock('Troupe\Executor');
    $this->url = 'http://git.source.com/example';
    $this->data_dir = 'a/path/to/a/directory';
    $this->vdm = $this->quickMock('Troupe\VendorDirectory\Manager', array('isDataImported', 'importSuccess'));
    $this->source = $this->getMock(
      'Troupe\Source\CommandlineImport',
      array(
        'getCliCheckOutCommand', 'checkIfCheckoutSuccess',
        'getCliUpdateCommand', 'checkIfUpdateSuccess'
      ),
      array($this->url, $this->vdm, $this->executor, $this->data_dir)
    );
  }
  
  function testImportChecksWithVdmIfDataHasAlreadyBeenImported() {
    $this->vdm->expects($this->once())
      ->method('isDataImported')
      ->with($this->url);
    $this->source->import();
  }
  
  private function vdmIsDataImported($bool) {
    return $this->vdm->expects($this->once())
      ->method('isDataImported')
      ->will($this->returnValue($bool));
  }

  function testImportUsesValueFromGetCliCheckOutCommand() {
    $this->vdmIsDataImported(false);
    $this->source->expects($this->once())
      ->method('getCliCheckOutCommand')
      ->will($this->returnValue('my command'));
    $this->executor->expects($this->once())
      ->method('system')
      ->with('my command');
    $this->source->import();
  }
  
  function testImportTestsCliCheckOutCommandResultAgainstCheckoutSuccessTest() {
    $this->vdmIsDataImported(false);
    $this->source->expects($this->once())
      ->method('getCliCheckOutCommand')
      ->will($this->returnValue('my command'));
    $this->executor->expects($this->once())
      ->method('system')
      ->with('my command')
      ->will($this->returnValue('Success!'));
    $this->source->expects($this->once())
      ->method('checkIfCheckoutSuccess')
      ->with('Success!');
    $this->source->import();
  }
  
  function testImportSkipsCheckoutIfDataHasAlreadyBeenImported() {
    $this->vdmIsDataImported(true);
    $this->executor->expects($this->never())
      ->method('system');
    $this->source->import();
  }
  
  function checkOutIsSuccessful() {
    $this->vdmIsDataImported(false);
    $this->source->expects($this->once())
      ->method('checkIfCheckoutSuccess')
      ->will($this->returnValue(true));
  }
  
  function testImportReturnsOkayStatusMessageIfSuccessful() {
    $folder_name = md5($this->url);
    $status = new Success(
      \Troupe\Source\STATUS_OK, "SUCCESS: Imported {$this->url}.",
      "{$this->data_dir}/$folder_name"
    );
    $this->checkoutIsSuccessful();
    $this->assertEquals($status, $this->source->import());
  }
  
  function testImportTellsVdmThatTheDataHasBeenImportedSuccessfuly() {
    $this->checkoutIsSuccessful();
    $this->vdm->expects($this->once())
      ->method('importSuccess')
      ->with($this->url);
    $this->source->import();
  }
  
  function testImportReturnsOkayStatusMessageIfVdmSaysDataHasAlreadyBeenImported() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(true);
    $status = new Success(
      \Troupe\Source\STATUS_OK, "SUCCESS: {$this->url} has already been imported.",
      "{$this->data_dir}/$folder_name"
    );
    $this->assertEquals($status, $this->source->import());
  }
  
  private function checkOutIsFailure() {
    $this->vdmIsDataImported(false);
    $this->source->expects($this->once())
      ->method('checkIfCheckoutSuccess')
      ->will($this->returnValue(false));
  }
  
  function testImportReturnsFailStatusMessageIfNotSuccessful() {
    $this->checkOutIsFailure();
    $status = new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unable to import {$this->url}."
    );
    $this->assertEquals($status, $this->source->import());
  }
  
  function testImportSkipsImportSuccessIfNotSuccessful() {
    $folder_name = md5($this->url);
    $this->checkOutIsFailure();
    $this->vdm->expects($this->never())
      ->method('importSuccess');
    $this->source->import();
  }
  
  function testUpdateChecksWithVdmIfDataHasAlreadyBeenImported() {
    $this->vdm->expects($this->atLeastOnce())
      ->method('isDataImported')
      ->with($this->url);
    $this->source->update();
  }
  
  function testUpdateUsesValueFromGetCliUpdateCommand() {
    $this->vdmIsDataImported(true);
    $this->source->expects($this->once())
      ->method('getCliUpdateCommand')
      ->will($this->returnValue('my update command'));
    $this->executor->expects($this->once())
      ->method('system')
      ->with('my update command');
    $this->source->update();
  }
  
  private function mockSourceWithImport() {
    $this->source = $this->getMock(
      'Troupe\Source\CommandlineImport',
      array(
        'getCliCheckOutCommand', 'checkIfCheckoutSuccess', 'import',
        'getCliUpdateCommand', 'checkIfUpdateSuccess'
      ),
      array($this->url, $this->vdm, $this->executor, $this->data_dir)
    );
  }
  
  function testUpdateUsesImportIfDataHasntBeenImportedYet() {
    $this->vdm->expects($this->atLeastOnce())
      ->method('isDataImported')
      ->will($this->returnValue(false));
    $this->mockSourceWithImport();
    $this->source->expects($this->never())
      ->method('getCliUpdateCommand');
    $this->source->expects($this->once())
      ->method('import');
    $this->source->update();
  }
  
  function testUpdateReturnsValueFromImport() {
    $this->vdm->expects($this->atLeastOnce())
      ->method('isDataImported')
      ->will($this->returnValue(false));
    $status = $this->quickMock('\Troupe\Status\Success');
    $this->mockSourceWithImport();
    $this->source->expects($this->once())
      ->method('import')
      ->will($this->returnValue($status));
    $this->assertEquals(
      $status, $this->source->update()
    );
  }
  
  function testUpdateTestsCliUpdateCommandResultAgainstUpdateSuccessTest() {
    $this->vdmIsDataImported(true);
    $this->source->expects($this->once())
      ->method('getCliUpdateCommand')
      ->will($this->returnValue('my command'));
    $this->executor->expects($this->once())
      ->method('system')
      ->with('my command')
      ->will($this->returnValue('Success!'));
    $this->source->expects($this->once())
      ->method('checkIfUpdateSuccess')
      ->with('Success!');
    $this->source->update();
  }
  
  function testUpdateReturnsOkayStatusMessageIfSuccessful() {
    $folder_name = md5($this->url);
    $status = new Success(
      \Troupe\Source\STATUS_OK, "SUCCESS: Updated {$this->url}.",
      "{$this->data_dir}/$folder_name"
    );
    $this->vdmIsDataImported(true);
    $this->source->expects($this->once())
      ->method('checkIfUpdateSuccess')
      ->will($this->returnValue(true));
    $this->assertEquals($status, $this->source->update());
  }
  
  function testUpdateReturnsFailStatusMessageIfNotSuccessful() {
    $this->vdmIsDataImported(true);
    $this->source->expects($this->once())
      ->method('checkIfUpdateSuccess')
      ->will($this->returnValue(false));
    $status = new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unable to update {$this->url}."
    );
    $this->assertEquals($status, $this->source->update());
  }
  
}
