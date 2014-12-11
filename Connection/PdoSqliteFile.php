<?php
/**
 * Class file of bytepark database migration pdo sqlite file connection
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
 * PDO sqlite file connection of bytepark database migration Toolkit
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Connection
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class PdoSqliteFile extends AbstractPdo
{
    /**
     * Instantiates a sqlite file storage engine using connection
     *
     * @param \SplFileInfo $file The file to use as store
     */
    public function __construct(\SplFileInfo $file)
    {
        $this->connection = new \PDO('sqlite:'.$file->getPathname());
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
