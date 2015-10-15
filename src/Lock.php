<?php
/**
 * File of bytepark database migration lock interface
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

namespace Bytepark\Component\Migration;

/**
 * Lock interface of bytepark database migration Toolkit
 *
 * Interface to implement locking mechanisms.
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Lock
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
interface Lock
{
    /**
     * Acquires the lock
     *
     * @throws \Bytepark\Component\Migration\Exception\LockNotAcquirableException When lock cannot be acquired
     *
     * @return void
     */
    public function acquire();

    /**
     * Releases the lock
     *
     * @return void
     */
    public function release();
}
