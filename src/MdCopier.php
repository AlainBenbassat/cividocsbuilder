<?php
declare(strict_types=1);

namespace BuildCividocs;

class MdCopier {
  public function __construct(bool $removeExistingFiles = FALSE) {
    if ($removeExistingFiles) {
      $this->initTargetDir();
    }
  }

  public function initTargetDir() {
    $to = __DIR__ . "/../output";

    // remove existing files
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

    Logger::write("Copying $from to $to");
    exec("cp -r $from $to", $output, $resultCode);

    if ($resultCode !== 0) {
      throw new \Exception("Copying $source to $destination failed");
    }
  }

  public function copyCssAndJavascript() {
    $output = null;
    $resultCode = null;

    $from = __DIR__ . "/../assets/css";
    $to = __DIR__ . "/../output/docs";

    Logger::write("Copying $from to $to");
    exec("cp -r $from $to", $output, $resultCode);

    if ($resultCode !== 0) {
      throw new \Exception("Copying css folder failed");
    }
  }

  public function copyImages() {
    $output = null;
    $resultCode = null;

    $from = __DIR__ . "/../assets/img";
    $to = __DIR__ . "/../output/docs";

    Logger::write("Copying $from to $to");
    exec("cp -r $from $to", $output, $resultCode);

    if ($resultCode !== 0) {
      throw new \Exception("Copying img folder failed");
    }
  }

  public function copyTags() {
    $output = null;
    $resultCode = null;

    $from = __DIR__ . "/../assets/tags.md";
    $to = __DIR__ . "/../output/docs";

    Logger::write("Copying $from to $to");
    exec("cp $from $to", $output, $resultCode);

    if ($resultCode !== 0) {
      throw new \Exception("Copying tags.md failed");
    }
  }
}
