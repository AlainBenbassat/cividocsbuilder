<?php

namespace BuildCividocs;

class Logger {
  public static function write($message) {
    $sep = str_repeat('=', strlen($message));
    echo "\n$sep\n";
    echo "$message\n";
    echo "$sep\n";
  }
}