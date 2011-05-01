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
    $this->vdm = $this->quickMock(
      'Troupe\VendorDirectory\Manager',
      array('isDataImported', 'importSuccess')
    );
    $this->git_source = new Git(
      $this->url, $this->vdm, $this->executor, $this->data_dir
    );
  }

  function testGetCliCheckOutCommand() {
    $folder_name = md5($this->url);
    $this->assertEquals(
      "git clone --recursive '{$this->url}' '{$this->data_dir}/$folder_name'",
      $this->git_source->getCliCheckOutCommand($this->url, "{$this->data_dir}/$folder_name")
    );
  }
  
  function testGetCliUpdateCommand() {
    $folder_name = md5($this->url);
    $this->assertEquals(
      "cd '{$this->data_dir}/$folder_name' && git pull origin && git " .
      "submodule foreach git pull origin master",
      $this->git_source->getCliUpdateCommand(
        $this->url, "{$this->data_dir}/$folder_name"
      )
    );
  }

}
