<?php
/**
 * Created by PhpStorm.
 * User: twhiston
 * Date: 16.01.17
 * Time: 23:30
 */

namespace Px\DrupalFeatureBranchDiviner\Tests;

include __DIR__ . '/../vendor/autoload.php';

use Px\CodeshipFeatureBranchDirectoryFinder\CodeshipFeatureBranchGroupFinder;

/**
 * Class CodeshipFeatureBranchGroupFinderTest
 *
 * @package Px\DrupalFeatureBranchDiviner\Tests
 */
class CodeshipFeatureBranchGroupFinderTest extends \PHPUnit_Framework_TestCase {

  /**
   * Runs testSetModuleNamePrefix.
   */
  public function testSetModuleNamePrefix() {
    $groupFinder = new CodeshipFeatureBranchGroupFinder();
    $groupFinder->setModuleNamePrefix('features');
    $this->assertEquals('features', $groupFinder->getModuleNamePrefix());
  }

  /**
   * Runs testSetModuleNamePrefix.
   *
   * @dataProvider getBranchNames
   */
  public function testDivineMethodToGetTheRightBranchName($prefix,
                                                          $branchName,
                                                          $expected) {
    $groupFinder = new CodeshipFeatureBranchGroupFinder();
    $groupFinder->setModuleNamePrefix($prefix);
    $this->assertEquals($expected, $groupFinder->divine($branchName));
  }

  /**
   * DataProvider
   */
  public function getBranchNames() {
    return [
      ['feature/', 'feature/123__my_custom__code_44_1', 'my_custom__code_44_1'],
      ['aaa/', 'aaa/123__my_custom__code_44_1', 'my_custom__code_44_1'],
      [
        'branchx',
        'branchx/123__my_custom__code_44_1',
        '/123__my_custom__code_44_1',
      ],
      ['feature/', 'feature/my_custom', 'my_custom'],
    ];
  }

}
