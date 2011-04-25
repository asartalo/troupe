<?php

namespace Troupe\Source;

class Svn extends CommandlineImport {
  
  function getCliCheckOutCommand($url, $troupe_lib_path) {
    return sprintf(
      'svn co %s %s', escapeshellarg($url), escapeshellarg($troupe_lib_path)
    );
  }
  
  function checkIfCheckoutSuccess($last_line) {
    return strpos($last_line, 'Checked out revision ') === 0;
  }
  
  function getCliUpdateCommand($url, $troupe_lib_path) {
    return sprintf(
      "svn update %s", escapeshellarg($troupe_lib_path)
    );
  }
  
  function checkIfUpdateSuccess($last_line) {
    return (
      preg_match('/^At revision [1-9][0-9]*/', $last_line) > 0 ||
      preg_match('/^Updated to revision [1-9][0-9]*/', $last_line) > 0
    );
  }
  
}
