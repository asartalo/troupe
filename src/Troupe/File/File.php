<?php

namespace Troupe\File;

interface File {
  function getContents();
  function getPath();
  function getType();
  function isFileExists();
  function setContents($contents);
  function save();
}
