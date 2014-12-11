<?php
/**
 * Class file of bytepark database migration connection interface
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Connection
 * @author     bytepark GmbH <code@bytepark.de>
 * @copyright  2014 - bytepark GmbH
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */

namespace Bytepark\Component\Migration;

use Bytepark\Component\Migration\Exception\QueryNotSuccessfulException;

/**
 * Connection interface of bytepark database migration Toolkit
 *
 * Interface to implement adapters to specific database connection implementations.
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Connection
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
interface Connection
{
    /**
     * Executes the given query
     *
     * @param string  $query      The query to execute
     * @param mixed[] $parameters Optional query parameters
     *
     * @throws QueryNotSuccessfulException
     *
     * @return boolean Whether the execution was successful
     */
    public function execute($query, array $parameters = null);

    /**
     * Queries the database with the given query
     *
     * @param string $query The query to execute
     * @param mixed[] $parameters Optional query parameters
     *
     * @throws QueryNotSuccessfulException
     *
     * @return array result set
     */
    public function query($query, array $parameters = null);
}
