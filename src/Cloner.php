<?php

namespace BuildCividocs;

class Cloner {
  public function clone($source): void {
    $output = null;
    $resultCode = null;

    chdir(__DIR__ . '/../input');
    exec("git clone --depth 1 $source", $output, $resultCode);

    if ($resultCode !== 0) {
      throw new \Exception("Cloning $source failed");
    }
  }
}