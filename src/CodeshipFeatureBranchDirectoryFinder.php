<?php
/**
 * Created by PhpStorm.
 * User: twhiston
 * Date: 16.01.17
 * Time: 23:18
 */

namespace Px\CodeshipFeatureBranchDirectoryFinder;

use Symfony\Component\Finder\Finder;

/**
 * Class CodeshipFeatureBranchDirectoryFinder
 *
 * @package Px\CodeshipFeatureBranchDirectoryFinder
 */
class CodeshipFeatureBranchDirectoryFinder {

  /**
   * @var string
   */
  private $moduleNamePrefix = 'feature/';

  /**
   * @var string
   */
  private $modulePath = '/opt/app-root/src/app/docroot/modules';

  /**
   * @var bool
   */
  private $softFail = FALSE;

  /**
   * @var string
   */
  private $softFailPathSuffix = 'custom';

  /**
   * @return string
   */
  public function getModuleNamePrefix(): string {
    return $this->moduleNamePrefix;
  }

  /**
   * @param string $moduleNamePrefix
   */
  public function setModuleNamePrefix(string $moduleNamePrefix) {
    $this->moduleNamePrefix = $moduleNamePrefix;
  }

  /**
   * @return string
   */
  public function getModulePath(): string {
    return $this->modulePath;
  }

  /**
   * @param string $modulePath
   */
  public function setModulePath(string $modulePath) {
    $this->modulePath = $modulePath;
  }

  /**
   * @return bool
   */
  public function isSoftFail(): bool {
    return $this->softFail;
  }

  /**
   * @param bool $softFail
   */
  public function setSoftFail(bool $softFail = TRUE) {
    $this->softFail = $softFail;
  }

  /**
   * @return string
   */
  public function getSoftFailPathSuffix(): string {
    return $this->softFailPathSuffix;
  }

  /**
   * @param string $softFailPathSuffix
   */
  public function setSoftFailPathSuffix(string $softFailPathSuffix) {
    $this->softFailPathSuffix = $softFailPathSuffix;
  }

  /**
   * @param string $ciBranch
   *
   * @return mixed
   * @throws \RuntimeException
   */
  public function divine(string $ciBranch) {

    $ciBranch = str_replace($this->moduleNamePrefix, '', $ciBranch);

    $finder = new Finder();
    $finder->depth('< 2');
    $finder->in($this->modulePath)
           ->directories()
           ->path($ciBranch);
    $file = $this->getFirstDir($finder);

    if ($file === NULL) {
      //returning 1 here is an error code and will fail the build
      if ($this->softFail) {
        return $this->getSoftFailPath();
      }
      throw new \RuntimeException('cannot find folder ' . $this->modulePath .
                                  '/*/*/' . $ciBranch);
    }

    return $file->getRealPath();

  }

  /**
   * @return string
   */
  protected function getSoftFailPath() {

    $finder = new Finder();
    $finder->depth('== 0');
    $finder->in($this->modulePath)
           ->directories()
           ->path($this->softFailPathSuffix);

    $file = $this->getFirstDir($finder);

    return $file === NULL ? $this->modulePath : $file->getRealPath();

  }

  /**
   * @param Finder $finder
   *
   * @return mixed
   */
  protected function getFirstDir(Finder $finder) {
    //get first occurance only
    $iterator = $finder->getIterator();
    $iterator->rewind();
    return $iterator->current();
  }

}