<?php
/**
 * Class file of bytepark database migration Toolkit exception LockNotAcquirableException
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Exception
 * @author     bytepark GmbH <code@bytepark.de>
 * @copyright  2014 - bytepark GmbH
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */

namespace Bytepark\Component\Migration\Exception;

/**
 * Exception class of bytepark database migration Toolkit LockNotAcquirableException
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Exception
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class UnitNotPresentException extends MigrationException
{
    protected $message = 'The UnitOfWork is not present.';
}
