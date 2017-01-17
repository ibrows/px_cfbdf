<?php
/**
 * Created by PhpStorm.
 * User: twhiston
 * Date: 16.01.17
 * Time: 23:18
 */

namespace Px\CodeshipFeatureBranchDirectoryFinder;

/**
 * Class CodeshipFeatureBranchDirectoryFinder
 *
 * @package Px\CodeshipFeatureBranchDirectoryFinder
 */
class CodeshipFeatureBranchGroupFinder {

  /**
   * @var string
   */
  private $moduleNamePrefix = 'feature/';

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
   * @param string $ciBranch
   *
   * @return mixed
   * @throws \RuntimeException
   */
  public function divine(string $ciBranch) {
    return str_replace($this->moduleNamePrefix, '', $ciBranch);
  }

}