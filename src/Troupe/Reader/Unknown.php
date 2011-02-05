<?php

namespace Troupe\Reader;

class Unknown implements Reader {
	
	function getDependencyList() {
	  return array();
  }
  
  function getSettings() {
    return array();
  }
  
}
