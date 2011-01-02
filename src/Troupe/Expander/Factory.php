<?php

namespace Troupe\Expander;

use \Troupe\Expander\Zip;

class Factory {
  
  function getExpander($url) {
    return new Zip;
  }
  
}
