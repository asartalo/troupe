<?php

namespace Troupe\Reader;

interface Reader {
	function getDependencyList();
  function getSettings();
}