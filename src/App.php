<?php

namespace BuildCividocs;

class App {
  private const REPO_USER_MANUAL = 'https://lab.civicrm.org/documentation/docs/user-en.git';
  private const REPO_SYSADMIN_MANUAL = 'https://lab.civicrm.org/documentation/docs/sysadmin.git';
  private const REPO_INSTALLATION_MANUAL = 'https://lab.civicrm.org/documentation/docs/installation.git';
  private const REPO_DEV_MANUAL = 'https://lab.civicrm.org/documentation/docs/dev.git';

  public function run() {
    $this->cloneRepos();
  }

  private function cloneRepos() {
    Cloner::clone(self::REPO_USER_MANUAL);
    Cloner::clone(self::REPO_SYSADMIN_MANUAL);
    Cloner::clone(self::REPO_INSTALLATION_MANUAL);
    Cloner::clone(self::REPO_DEV_MANUAL);
  }
}
