<?php

namespace Troupe\Source;

class Git extends CommandlineImport {
  
  function getCliCheckOutCommand($url, $troupe_lib_path) {
    return sprintf(
      'git clone --recursive %s %s',
      escapeshellarg($url), escapeshellarg($troupe_lib_path)
    );
  }
  
  function checkIfCheckoutSuccess($last_line) {
    return strpos($last_line, 'Initialized empty Git repository in') === 0 ||
      (
        strpos($last_line, 'Submodule path ') === 0 &&
        strpos($last_line, 'checked out ') > 16
      );
  }
  
  function getCliUpdateCommand($url, $troupe_lib_path) {
    return sprintf(
      'cd %s && git pull origin && git submodule foreach git pull',
      escapeshellarg($troupe_lib_path)
    );
  }
  
  function checkIfUpdateSuccess($last_line) {
    return (
      preg_match('/^Already up-to-date/', $last_line) > 0 ||
      preg_match('/^Fast-forward/', $last_line) > 0
    );
  }
  
}
