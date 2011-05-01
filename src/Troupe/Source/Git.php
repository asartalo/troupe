<?php

namespace Troupe\Source;

class Git extends CommandlineImport {
  
  function getCliCheckOutCommand($url, $troupe_lib_path) {
    return sprintf(
      'git clone --recursive %s %s',
      escapeshellarg($url), escapeshellarg($troupe_lib_path)
    );
  }
  
  function getCliUpdateCommand($url, $troupe_lib_path) {
    return sprintf(
      'cd %s && git pull origin && git submodule foreach git pull origin master',
      escapeshellarg($troupe_lib_path)
    );
  }
  
}
