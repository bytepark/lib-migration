<?php
/**
 * Class file of FilesystemWithAutoRelease lock test cases
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @copyright  2014 - bytepark GmbH
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */

namespace Bytepark\Component\Migration\Test\Lock;

use Bytepark\Component\Migration\Lock\FilesystemWithAutoRelease;

/**
 * Test cases for the filesystem with automatic release lock
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class FilesystemWithAutoReleaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @{inheritdoc}
     */
    protected function setUp()
    {
    }

    /**
     * @{inheritdoc}
     */
    protected function tearDown()
    {
        if (is_file(TEST_LOCK_FILE)) {
            unlink(TEST_LOCK_FILE);
        }
    }

    public function testSuccessfulConstruction()
    {
        $lock = $this->buildLockWithTimeDelta(1);
        $this->assertInstanceOf('Bytepark\Component\Migration\Lock\FilesystemWithAutoRelease', $lock);
    }

    public function testSuccessfulAcquireAfterTimeDelta()
    {
        $lock1 = $this->buildLockWithTimeDelta(1);
        $lock2 = $this->buildLockWithTimeDelta(1);
        $lock1->acquire();

        sleep(2);

        $lock2->acquire();

        $this->assertFileExists(TEST_LOCK_FILE);
    }

    public function testAcquireThrowsLockNotAcquirableExceptionBeforeTimeDelta()
    {
        $lock1 = $this->buildLockWithTimeDelta(5);
        $lock2 = $this->buildLockWithTimeDelta(1);
        $lock1->acquire();

        $this->setExpectedException('Bytepark\Component\Migration\Exception\LockNotAcquirableException');

        $lock2->acquire();
    }

    private function buildLockWithTimeDelta($deltaInSeconds)
    {
        $fileInfo = new \SplFileInfo(TEST_LOCK_FILE);
        $lockUntil = new \DateTime();
        $lockUntil->add(new \DateInterval(sprintf('PT%dS', $deltaInSeconds)));

        return new FilesystemWithAutoRelease($fileInfo, $lockUntil);
    }
}
