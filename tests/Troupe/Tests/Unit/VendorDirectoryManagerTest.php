<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\VendorDirectoryManager;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;

class VendorDirectoryManagerTest extends \Troupe\Tests\TestCase {
  
  function setUp() {
    $this->utilities = $this->quickMock(
      'Troupe\SystemUtilities',
      array('symlink', 'readlink', 'fileExists', 'unlink')
    );
    $this->project_link = 'a/link/path';
    $this->source_path = 'the/original/vendor/path';
    $this->VDM = new VendorDirectoryManager($this->utilities);
  }
  
  function testLinkingChecksIfLinkExistsFirst() {
    $this->utilities->expects($this->once())
      ->method('fileExists')
      ->with($this->project_link);
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
  function testLinkDirectoriesWhenLinkFileDoesNotExist() {
    $this->utilities->expects($this->once())
      ->method('fileExists')
      ->with($this->project_link)
      ->will($this->returnValue(false));
    $this->utilities->expects($this->once())
      ->method('symlink')
      ->with($this->source_path, $this->project_link);
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
  function testSkipLinkingWhenLinkAlreadyPointsToSourcePath() {
    $this->utilities->expects($this->once())
      ->method('fileExists')
      ->will($this->returnValue(true));
    $this->utilities->expects($this->once())
      ->method('readlink')
      ->with($this->project_link)
      ->will($this->returnValue($this->source_path));
    $this->utilities->expects($this->never())
      ->method('symlink');
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
  function testSkipReadLinkWhenFileDoesNotExist() {
    $this->utilities->expects($this->once())
      ->method('fileExists')
      ->will($this->returnValue(false));
    $this->utilities->expects($this->never())
      ->method('readlink');
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
  function testLinkDirectoriesWhenLinkDoesNotPointToSourcePath() {
    $this->utilities->expects($this->once())
      ->method('fileExists')
      ->will($this->returnValue(true));
    $this->utilities->expects($this->once())
      ->method('readlink')
      ->with($this->project_link)
      ->will($this->returnValue('foobar'));
    $this->utilities->expects($this->once())
      ->method('symlink')
      ->with($this->source_path, $this->project_link);
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
  function testLinkDirectoriesDeletesLinkWhenLinkDoesNotPointToSourcePath() {
    $this->utilities->expects($this->at(0))
      ->method('fileExists')
      ->will($this->returnValue(true));
    $this->utilities->expects($this->at(1))
      ->method('readlink')
      ->with($this->project_link)
      ->will($this->returnValue('foobar'));
    $this->utilities->expects($this->at(2))
      ->method('unlink')
      ->with($this->project_link);
    $this->utilities->expects($this->at(3))
      ->method('symlink')
      ->with($this->source_path, $this->project_link);
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
}