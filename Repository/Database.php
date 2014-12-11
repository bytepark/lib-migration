<?php
/**
 * Class file of bytepark database migration Toolkit database repository
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Repository
 * @author     bytepark GmbH <code@bytepark.de>
 * @copyright  2014 - bytepark GmbH
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */

namespace Bytepark\Component\Migration\Repository;

use Bytepark\Component\Migration\Connection;
use Bytepark\Component\Migration\Factory\UnitOfWorkFactory;
use Bytepark\Component\Migration\Repository;
use Bytepark\Component\Migration\UnitOfWork;

/**
 * Database repository
 *
 * The repository hold all migrations from a database
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Repository
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class Database extends AbstractRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var array
     */
    private $presentUidsOnConstruction = array();

    /**
     * Instantiates the repository for the underlying database
     *
     * A Connection interface implementation and the table name of the migration
     * store have to be injected.
     *
     * @param Connection $connection The connection to use
     * @param string     $tableName  The name of the database table to use
     */
    public function __construct(Connection $connection, $tableName)
    {
        $this->connection = $connection;
        $this->tableName = $tableName;

        $this->setupDatabaseTable();
        $this->buildMigrations();
    }

    /**
     * @{inheritdoc}
     */
    public function persist()
    {
        foreach ($this as $uid => $unitOfWork) {
            /* @var $unitOfWork UnitOfWork */
            if (!isset($this->presentUidsOnConstruction[$uid])) {
                $this->connection->execute(
                    sprintf("INSERT INTO %s VALUES (NULL, ?, ? , ?)", $this->tableName),
                    array (
                        $unitOfWork->getUniqueId(),
                        $unitOfWork->getQuery(),
                        $unitOfWork->getMigrationDateTime()->format('Y-m-d H:i:s')
                    )
                );
                $this->presentUidsOnConstruction[$unitOfWork->getUniqueId()] = true;
            }
        }

        return true;
    }

    /**
     * Setup of the database
     *
     * @return bool
     */
    private function setupDatabaseTable()
    {
        $query = sprintf(
            "CREATE TABLE IF NOT EXISTS %s (id INTEGER AUTO_INCREMENT PRIMARY KEY, "
            . "unique_id VARCHAR(255), query TEXT, migrated_at DATETIME);",
            $this->tableName
        );

        $this->connection->execute($query);
    }

    /**
     * Builds the migrations from the database
     *
     * @return void
     */
    private function buildMigrations()
    {
        $unitDataArray = $this->loadUnitDataIteratorFromDatabase();
        foreach ($unitDataArray as $unitData) {
            $unitOfWork = UnitOfWorkFactory::BuildFromFlatArray($unitData);
            $this->presentUidsOnConstruction[$unitOfWork->getUniqueId()] = true;
            $this->add($unitOfWork);
        }
    }

    /**
     * Loads the UnitsOfWork from the database as Iterator
     *
     * @return \ArrayIterator
     */
    private function loadUnitDataIteratorFromDatabase()
    {
        $result = $this->connection->query(
            sprintf("SELECT * FROM %s ORDER BY unique_id ASC", $this->tableName)
        );

        return new \ArrayIterator($result);
    }
}
