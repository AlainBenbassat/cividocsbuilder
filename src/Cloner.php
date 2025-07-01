<?php

namespace BuildCividocs;

class Cloner {
  public function clone(string $source): void {
    $output = null;
    $resultCode = null;

    // pull or clone the code
    $installationDir = $this->extractInstallationDir($source);
    if (is_dir(__DIR__ . "/../input/$installationDir")) {
      $action ='Pulling';
      Logger::write("$action $source");

      chdir(__DIR__ . "/../input/$installationDir");
      exec('git pull', $output, $resultCode);
    }
    else {
      $action = 'Cloning';
      Logger::write("$action $source");

      chdir(__DIR__ . '/../input');
      exec("git clone --depth 1 $source", $output, $resultCode);
    }

    if ($resultCode !== 0) {
      throw new \Exception("Cloning $source failed");
    }
  }

  private function extractInstallationDir(string $source): string {
    return str_replace('.git', '', substr($source, strrpos($source, '/') + 1));
  }
}