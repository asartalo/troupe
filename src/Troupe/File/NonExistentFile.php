<?php

namespace Troupe\File;

class NonExistentFile implements File {
  
  function getContents() {
    return '';
  }
  
  function getPath() {
    return '';
  }
  
  function getType() {
    return '';
  }
  
  function isFileExists() {
    return false;
  }
  
  function setContents($contents) {}
  
  function save() {}
  
}
