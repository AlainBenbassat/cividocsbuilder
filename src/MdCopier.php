<?php

namespace BuildCividocs;

class MdCopier {
  public function initTargetDir() {
    $to = __DIR__ . "/../output";
    shell_exec("rm -rf $to/docs");
    shell_exec("rm -rf $to/mkdocs.yml");
  }

  public function copyRepo($source, $destination): void {
    $output = null;
    $resultCode = null;

    $from = __DIR__ . "/../input/$source/docs/*";
    $to = __DIR__ . "/../output/docs/$destination";

    if (!file_exists($to)) {
      mkdir($to, 0775, true);
    }

    exec("cp -r $from $to", $output, $resultCode);

    if ($resultCode !== 0) {
      throw new \Exception("Copying $source to $destination failed");
    }
  }

  public function copyAbout() {
    $output = null;
    $resultCode = null;

    $from = __DIR__ . "/../assets/about";
    $to = __DIR__ . "/../output/docs";

    exec("cp -r $from $to", $output, $resultCode);

    if ($resultCode !== 0) {
      throw new \Exception("Copying $source to $destination failed");
    }
  }
}
