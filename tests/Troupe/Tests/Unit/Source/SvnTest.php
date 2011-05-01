<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Svn;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;


class SvnTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->executor  = $this->getMock('Troupe\Executor');
    $this->url = 'http://svn.source.com/example';
    $this->data_dir = 'a/path/to/a/directory';
    $this->vdm = $this->quickMock('Troupe\VendorDirectory\Manager');
    $this->svn_source = new Svn($this->url, $this->vdm, $this->executor, $this->data_dir);
  }

  function testGetCliCheckOutCommand() {
    $folder_name = md5($this->url);
    $this->assertEquals(
      "svn co '{$this->url}' '{$this->data_dir}/$folder_name'",
      $this->svn_source->getCliCheckOutCommand($this->url, "{$this->data_dir}/$folder_name")
    );
  }
  function testGetCliUpdateCommand() {
    $folder_name = md5($this->url);
    $this->assertEquals(
      "svn update '{$this->data_dir}/$folder_name'",
      $this->svn_source->getCliUpdateCommand($this->url, "{$this->data_dir}/$folder_name")
    );
  }
  
}
