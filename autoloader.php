<?php

spl_autoload_register('autoloaderFunction');

function autoloaderFunction($className) {
  $path = __DIR__ . '/';
  $extension = ".php";
  $fullPath = $path . $className . $extension;

  if(!file_exists($fullPath)) {
    return false;
  }

  include_once $fullPath;
}
