<?php

namespace Troupe\Dependency;

interface DependencyInterface {
  function import();
  function update();
  function getName();
  function getLocalLocation();
  function getSource();
  function getDataLocation();
  function getUrl();
  function __toString();
}
