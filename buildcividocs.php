#!/usr/bin/env php
<?php

namespace BuildCividocs;

use Exception;

require_once __DIR__ . '/vendor/autoload.php';

function main() {
  try {
    $app = new App();
    $app->run();

    return 0;
  }
  catch (Exception $e) {
    echo $e->getMessage() . "\n";

    return 1;
  }
}

exit(main());

