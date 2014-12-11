<?php
/**
 * Class file of bytepark database migration pdo sqlite connection
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
 * PDO sqlite connection of bytepark database migration Toolkit
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Connection
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class PdoSqliteMemory extends AbstractPdo
{
    /**
     * Instantiates a sqlite memory storage engine using connection
     */
    public function __construct()
    {
        $this->connection = new \PDO('sqlite::memory:');
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
