<?php
declare(strict_types=1);

namespace BuildCividocs;

use Symfony\Component\Yaml\Yaml;

class App {
  private const REPO_INSTALLATION_MANUAL = 'https://lab.civicrm.org/documentation/docs/installation.git';
  private const REPO_USER_MANUAL = 'https://lab.civicrm.org/documentation/docs/user-en.git';
  private const REPO_SYSADMIN_MANUAL = 'https://lab.civicrm.org/documentation/docs/sysadmin.git';
  private const REPO_DEV_MANUAL = 'https://lab.civicrm.org/documentation/docs/dev.git';

  public function run(): void {
    $this->cloneReposIntoInputDir();
    $this->copyInputFilesToOutputDir();

    $this->generateMkdocsYmlFileIntoOutputDir();

    $this->buildStaticSite();
  }

  private function cloneReposIntoInputDir(): void {
    $cloner = new Cloner();

    $cloner->clone(self::REPO_SYSADMIN_MANUAL);
    $cloner->clone(self::REPO_USER_MANUAL);
    $cloner->clone(self::REPO_INSTALLATION_MANUAL);
    $cloner->clone(self::REPO_DEV_MANUAL);

    // TEMPORARY SOLUTION!!!
    // TODO: upload Home and About to https://lab.civicrm.org/documentation/docs
    $cloner->copyLocalRepo('home');
    $cloner->copyLocalRepo('about');
  }

  private function copyInputFilesToOutputDir(): void {
    $mdCopier = new MdCopier(TRUE);

    $mdCopier->copyRepo('home', '');
    $mdCopier->copyRepo('installation', 'installation');
    $mdCopier->copyRepo('user-en', 'user');
    $mdCopier->copyRepo('sysadmin', 'sysadmin');
    $mdCopier->copyRepo('dev', 'dev');
    $mdCopier->copyRepo('about', 'about');

    $mdCopier->copyCssAndJavascript();
    $mdCopier->copyImages();
    $mdCopier->copyTags();
  }

  private function generateMkdocsYmlFileIntoOutputDir(): void {
    Logger::write("Combining mkdocs.yml from all books");

    $targetYml = Yaml::parseFile(__DIR__ . "/../input/home/mkdocs.yml");

    $installationYml = Yaml::parseFile(__DIR__ . "/../input/installation/mkdocs.yml");
    $this->addTargetGuideName($installationYml, 'installation');

    $userYml = Yaml::parseFile(__DIR__ . "/../input/user-en/mkdocs.yml");
    $this->addTargetGuideName($userYml, 'user');

    $adminYml = Yaml::parseFile(__DIR__ . "/../input/sysadmin/mkdocs.yml");
    $this->addTargetGuideName($adminYml, 'sysadmin');

    $devYml = Yaml::parseFile(__DIR__ . "/../input/dev/mkdocs.yml");
    $this->addTargetGuideName($devYml, 'dev');

    $aboutYml = Yaml::parseFile(__DIR__ . "/../input/about/mkdocs.yml");
    $this->addTargetGuideName($aboutYml, 'about');

    $targetYml['nav'][] = [
      'User Guide' => $userYml['nav'],
    ];
    $targetYml['nav'][] = [
      'Installation Guide' => $installationYml['nav'],
    ];
    $targetYml['nav'][] = [
      'Administrator Guide' => $adminYml['nav'],
    ];
    $targetYml['nav'][] = [
      'Developer Guide' => $devYml['nav'],
    ];
    $targetYml['nav'][] = [
      'About' => $aboutYml['nav'],
    ];

    file_put_contents(__DIR__ . '/../output/mkdocs.yml', Yaml::dump($targetYml));
  }

  private function buildStaticSite(): void {
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
      elseif (is_string($v)) {
        if (str_contains($v, '.md')) {
          $baseArr[$k] = "$prefix/$v";
        }
      }
    }
  }
}
