<?php
/**
 * Created by PhpStorm.
 * User: twhiston
 * Date: 16.01.17
 * Time: 23:30
 */

namespace Px\DrupalFeatureBranchDiviner\Tests;

include __DIR__ . '/../vendor/autoload.php';

use Px\CodeshipFeatureBranchDirectoryFinder\CodeshipFeatureBranchDirectoryFinder;

class CodeshipFeatureBranchDirectoryFinderTest extends \PHPUnit_Framework_TestCase {

  protected $dirs = [];

  public function __construct($name = NULL, array $data = [], $dataName = '') {
    parent::__construct($name, $data, $dataName);
    $assetPrefix = __DIR__.'/assets';
    $this->dirs = [$assetPrefix ,$assetPrefix.'/module_dir',$assetPrefix.'/soft_suffix'];
  }

  protected function setUp() {
    parent::setUp();
    foreach ($this->dirs as $dir) {
      @mkdir($dir);
    }
  }

  protected function tearDown() {
    parent::tearDown();
    foreach (array_reverse($this->dirs) as $dir) {
      @rmdir($dir);
    }
  }

  /**
   * @expectedException \RuntimeException
   */
  public function testMissingDir() {

    $f = new CodeshipFeatureBranchDirectoryFinder();
    $f->divine('feature/sge_pagesvisited_fakeout');
  }

  public function testCodeshipFeatureBranchDirectoryFinder() {

    $f = new CodeshipFeatureBranchDirectoryFinder();
    $f->setModulePath(__DIR__ . '/assets');
    $path = $f->divine('feature/module_dir');
    $this->assertEquals(__DIR__ . '/assets/module_dir', $path);
  }

  public function testSoftFail() {

    $f = new CodeshipFeatureBranchDirectoryFinder();
    $f->setModulePath(__DIR__ . '/assets');
    $f->setSoftFail();
    $path = $f->divine('feature/fake_dir');
    $this->assertEquals(__DIR__ . '/assets', $path);
  }

  public function testSoftFailIncorrectSuffix() {

    $f = new CodeshipFeatureBranchDirectoryFinder();
    $f->setModulePath(__DIR__ . '/assets');
    $f->setSoftFail();
    $f->setSoftFailPathSuffix('fail_suffix');
    $path = $f->divine('feature/fake_dir');
    $this->assertEquals(__DIR__ . '/assets', $path);
  }

  public function testSoftFailSuffix() {

    $f = new CodeshipFeatureBranchDirectoryFinder();
    $f->setModulePath(__DIR__ . '/assets');
    $f->setSoftFail();
    $f->setSoftFailPathSuffix('soft_suffix');
    $path = $f->divine('feature/fake_dir');
    $this->assertEquals(__DIR__ . '/assets/soft_suffix', $path);
  }

}
