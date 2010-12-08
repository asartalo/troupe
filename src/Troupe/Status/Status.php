<?php

namespace Troupe\Status;

abstract class Status {
  
  private $status, $message, $attachment;
  
  function __construct($status, $message, $attachment = null) {
    $this->status     = $status;
    $this->message    = $message;
    $this->attachment = $attachment;
  }
  
  function getStatus() {
    return $this->status;
  }
  
  function getMessage() {
    return $this->message;
  }
  
  function getAttachment() {
    return $this->attachment;
  }
  
  abstract function isSuccessful();
  
}
