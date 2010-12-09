<?php

namespace Troupe\Source;

const STATUS_OK        = 10200;
const STATUS_OK_UPDATE = 10201;
const STATUS_FAIL      = 10300;

interface Source {
  function import();
  function getDataDir();
};
