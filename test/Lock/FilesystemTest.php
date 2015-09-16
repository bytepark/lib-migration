<?php
/**
 * Class file of Filesystem lock test cases
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

use Bytepark\Component\Migration\Lock\Filesystem;

/**
 * Test cases for the filesystem lock
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class FilesystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Filesystem
     */
    protected $lock;

    protected function setUp()
    {
        $fileInfo = new \SplFileInfo(TEST_LOCK_FILE);
        $this->lock = new Filesystem($fileInfo);
    }

    protected function tearDown()
    {
        if (is_file(TEST_LOCK_FILE)) {
            unlink(TEST_LOCK_FILE);
        }
    }

    public function testSuccessfulConstruction()
    {
        $this->assertInstanceOf('Bytepark\Component\Migration\Lock', $this->lock);
    }

    public function testAcquire()
    {
        $this->lock->acquire();

        $this->assertFileExists(TEST_LOCK_FILE);
    }

    public function testAcquireThrowsLockNotAcquirableException()
    {
        $lock = new Filesystem(new \SplFileInfo(TEST_LOCK_FILE));
        $lock->acquire();

        $this->setExpectedException('Bytepark\Component\Migration\Exception\LockNotAcquirableException');

        $this->lock->acquire();
    }

    public function testAcquireAndReleasedLockCanBeAcquired()
    {
        $lock = new Filesystem(new \SplFileInfo(TEST_LOCK_FILE));
        $lock->acquire();
        $lock->release();

        $this->lock->acquire();
    }

    public function testRelease()
    {
        $this->lock->acquire();
        $this->lock->release();

        $this->assertFileNotExists(TEST_LOCK_FILE);
    }
}
