<?php
/**
 * Class file of bytepark database migration Toolkit exception for unsuccessful queries
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
 * Unsuccessful SQL query exception class of bytepark database migration Toolkit
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Exception
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class QueryNotSuccessfulException extends MigrationException
{
    protected $message = 'The following query could NOT be performed: "%s"';

    /**
     * Constructs the exception with the erroneous query
     *
     * @param string $query
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct(
        $query = 'NOT PROVIDED',
        $message = '',
        $code = 0,
        Exception $previous = null
    ) {
        $message = sprintf($this->message, $query);

        parent::__construct($message, $code, $previous);
    }
}
