<?php
/**
 * File of bytepark database migration filesystem lock
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Lock
 * @author     bytepark GmbH <code@bytepark.de>
 * @copyright  2014 - bytepark GmbH
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */

namespace Bytepark\Component\Migration\Lock;

use Bytepark\Component\Migration\Lock;
use Bytepark\Component\Migration\Exception\LockNotAcquirableException;

/**
 * Filesystem lock implementation of bytepark database migration Toolkit
 *
 * Filesystem-based locking.
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Lock
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class Filesystem implements Lock
{
    /**
     * @var \SplFileInfo
     */
    private $fileInfo;

    public function __construct(\SplFileInfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }

    /**
     * @{inheritdoc}
     */
    public function acquire()
    {
        $this->assertThatLockIsAcquirable();

        $this->fileInfo->openFile('w');
    }

    /**
     * @{inheritdoc}
     */
    public function release()
    {
        unlink($this->fileInfo->getPathname());
    }

    /**
     * The file info
     *
     * @return \SplFileInfo
     */
    protected function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * Asserts that the lock can be acquired
     *
     * @throws LockNotAcquirableException
     *
     * @return void
     */
    private function assertThatLockIsAcquirable()
    {
        if ($this->fileInfo->isFile()) {
            throw new LockNotAcquirableException();
        }
    }
}
