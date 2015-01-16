<?php
/**
 * Class file of bytepark database migration pdo mysql connection
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

namespace Bytepark\Component\Migration\Connection;

use Bytepark\Component\Migration\Connection;

/**
 * PDO mysql connection of bytepark database migration Toolkit
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Connection
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class PdoMysql extends AbstractPdo
{
    /**
     * Instantiates a mysql connection
     *
     * @param string $host     The database server host name
     * @param string $database The database name
     * @param string $user     The username to connect with
     * @param string $password The password to connect with
     */
    public function __construct($host, $database, $user, $password)
    {
        $dsn = sprintf('mysql:dbname=%s;host=%s;charset=UTF8', $database, $host);
        $this->connection = new \PDO($dsn, $user, $password);
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
