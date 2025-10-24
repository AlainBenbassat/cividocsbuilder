<?php

namespace BuildCividocs;

use Symfony\Component\Yaml\Yaml;

class App {
  //private const REPO_USER_MANUAL = 'https://lab.civicrm.org/documentation/docs/user-en.git';
  private const REPO_USER_MANUAL = 'https://lab.civicrm.org/usha.makoa/user-en.git';
  private const REPO_SYSADMIN_MANUAL = 'https://lab.civicrm.org/documentation/docs/sysadmin.git';
  private const REPO_INSTALLATION_MANUAL = 'https://lab.civicrm.org/documentation/docs/installation.git';
  private const REPO_DEV_MANUAL = 'https://lab.civicrm.org/documentation/docs/dev.git';

  public function run() {
    $this->cloneRepos();
    $this->convertDocs();
    $this->generateMkdocsFile();
    $this->copyIndexAndOtherFiles();
    $this->buildStaticSite();
  }

  private function cloneRepos() {
    $cloner = new Cloner();
    
    $cloner->clone(self::REPO_USER_MANUAL);
    $cloner->clone(self::REPO_SYSADMIN_MANUAL);
    $cloner->clone(self::REPO_INSTALLATION_MANUAL);
    $cloner->clone(self::REPO_DEV_MANUAL);
  }

  private function convertDocs() {
    $mdCopier = new MdCopier();

    $mdCopier->initTargetDir();

    $mdCopier->copyRepo('user-en', 'user');
    $mdCopier->copyRepo('sysadmin', 'admin');
    $mdCopier->copyRepo('installation', 'installation');
    $mdCopier->copyRepo('dev', 'dev');

    $mdCopier->copyAbout();
  }

  private function copyIndexAndOtherFiles() {
    $mdCopier = new MdCopier();
    $mdCopier->copyIndex();
    $mdCopier->copyCssAndJavascript();
    $mdCopier->copyImages();
    $mdCopier->copyIndex();
    $mdCopier->copyTags();
  }

  private function generateMkdocsFile() {
    Logger::write("Combining mkdocs.yml from all books");

    $targetYml = Yaml::parseFile(__DIR__ . "/../assets/mkdocs.yml");

    $userYml = Yaml::parseFile(__DIR__ . "/../input/user-en/mkdocs.yml");
    $this->addTargetGuideName($userYml, 'user');

    $installationYml = Yaml::parseFile(__DIR__ . "/../input/installation/mkdocs.yml");
    $this->addTargetGuideName($installationYml, 'installation');

    $adminYml = Yaml::parseFile(__DIR__ . "/../input/sysadmin/mkdocs.yml");
    $this->addTargetGuideName($adminYml, 'admin');

    $devYml = Yaml::parseFile(__DIR__ . "/../input/dev/mkdocs.yml");
    $this->addTargetGuideName($devYml, 'dev');

    $targetYml['nav'] = [
      [
        'Home' => [
          'Welcome' => 'index.md',
        ],
      ],
      [
        'User Guide' => $userYml['nav'],
      ],
      [
        'Installation Guide' => $installationYml['nav'],
      ],
      [
        'Administrator Guide' => $adminYml['nav'],
      ],
      [
        'Developer Guide' => $devYml['nav'],
      ],
      [
        'About' => [
          'Introduction' => 'about/index.md',
          'Get Involved' => 'about/get-involved.md',
          'Style Guide' => 'about/style.md',
        ]
      ],
    ];

    file_put_contents(__DIR__ . '/../output/mkdocs.yml', Yaml::dump($targetYml));
  }

  private function buildStaticSite() {
    $output = null;
    $resultCode = null;

    Logger::write("Building the static site");
    chdir(__DIR__ . '/../output');
    exec("mkdocs build", $output, $resultCode);

    if ($resultCode !== 0) {
      throw new \Exception("Copying index.md failed");
    }
  }

  private function addTargetGuideName(&$baseArr, $prefix): void {
    foreach ($baseArr as $k => $v) {
      if (is_array($v)) {
        $this->addTargetGuideName($baseArr[$k], $prefix);
      }
      else {
        if (str_contains($v, '.md')) {
          $baseArr[$k] = "$prefix/$v";
        }
      }
    }
  }
}
