<?php
/**
 * Class file of bytepark database migration pdo base connection
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
use Bytepark\Component\Migration\Exception\QueryNotSuccessfulException;

/**
 * Base PDO connection of bytepark database migration Toolkit
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Connection
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
abstract class AbstractPdo implements Connection
{
    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * Gets the concrete PDO connection of the extending implementation
     *
     * @throws \LogicException
     *
     * @return \PDO
     */
    private function getConnection()
    {
        if (is_null($this->connection)) {
            throw new \LogicException('Connection must be instantiated in the constructor');
        }

        return $this->connection;
    }

    /**
     * @{inheritdoc}
     */
    public function execute($query, array $parameters = null)
    {
        try {
            $statement = $this->getConnection()->prepare($query);
            $statement->execute($parameters);
        } catch (\PDOException $e) {
            throw new QueryNotSuccessfulException($e->getMessage());
        }

        return true;
    }

    /**
     * @{inheritdoc}
     */
    public function query($query, array $parameters = null)
    {
        try {
            $statement = $this->getConnection()->prepare($query);
            $statement->execute($parameters);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new QueryNotSuccessfulException($e->getMessage());
        }

        return $result;
    }
}
