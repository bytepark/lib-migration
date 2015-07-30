<?php
/**
 * File of bytepark database migration filesystem lock with auto-release
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

use Bytepark\Component\Migration\Lock\Filesystem;
use Bytepark\Component\Migration\Exception\LockNotAcquirableException;

/**
 * Filesystem lock implementation of bytepark database migration Toolkit
 *
 * Filesystem-based locking with automatic release after a time delta.
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Lock
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class FilesystemWithAutoRelease extends Filesystem
{
    /**
     * @var \DateTime
     */
    private $autoReleaseMoment;

    /**
     * Constructs a new automatic releasing lock
     *
     * @param \SplFileInfo $fileInfo
     * @param \DateTime $autoReleaseMoment
     */
    public function __construct(
        \SplFileInfo $fileInfo,
        \DateTime $autoReleaseMoment
    ) {
        parent::__construct($fileInfo);
        $this->autoReleaseMoment = $autoReleaseMoment;
    }

    /**
     * @{inheritdoc}
     */
    public function acquire()
    {
        $this->releaseWhenLockIsPresentAndTimeDeltaAllows();

        parent::acquire();

        $this->writeAutoReleaseMoment();
    }

    /**
     * Releases the present lock if the datetime delta allows
     *
     * @return void
     */
    private function releaseWhenLockIsPresentAndTimeDeltaAllows()
    {
        $fileInfo = $this->getFileInfo();
        $lockIsPresent = $fileInfo->isFile();

        if ($lockIsPresent) {
            $this->releaseWhenTimeDeltaAllows();
        }
    }

    /**
     * Releases the lock if the datetime delta allows
     *
     * @return void
     */
    private function releaseWhenTimeDeltaAllows()
    {
        if ($this->canAutoRelease()) {
            $this->release();
        }
    }

    /**
     * Writes the datetime when to release into the lock file
     *
     * @return void
     */
    private function writeAutoReleaseMoment()
    {
        $fileObject = $this->getFileInfo()->openFile('w');
        $fileObject->fwrite($this->autoReleaseMoment->format('c'));
        $fileObject->fflush();
        $fileObject = null;
    }

    /**
     * Reads the datetime when to release from the lock file
     *
     * @return \DateTime
     */
    private function readAutoReleaseMoment()
    {
        $lockFile = $this->getFileInfo()->openFile('r');
        $lockEndTimeAsString = $lockFile->getCurrentLine();
        $lockFile = null;
        $lockEndTime = new \DateTime($lockEndTimeAsString);

        return $lockEndTime;
    }

    /**
     * Whether the datetime delta allows release of the lock
     *
     * @return boolean true, when the lock can be released, false otherwise
     */
    private function canAutoRelease()
    {
        $now = new \DateTime();
        $releaseMoment = $this->readAutoReleaseMoment();

        return $now > $releaseMoment;
    }
}
