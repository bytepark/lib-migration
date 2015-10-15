<?php
/**
 * Class file of Database repository test cases
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @copyright  2014 - bytepark GmbH
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */

namespace Bytepark\Component\Migration\Test\Repository;

use Bytepark\Component\Migration\Connection\PdoSqliteMemory;
use Bytepark\Component\Migration\Connection\PdoSqliteFile;
use Bytepark\Component\Migration\Repository\Database;
use Bytepark\Component\Migration\UnitOfWork;
use Bytepark\Component\Migration\UnitOfWork\Uid;
use Bytepark\Component\Migration\UnitOfWork\Workload;

/**
 * Test cases for the database repository
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class DatabaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var \Bytepark\Component\Migration\Connection
     */
    protected $connection;

    /**
     * @var \Bytepark\Component\Migration\Repository
     */
    protected $repository;

    /**
     * @var string
     */
    private $uidValue = 'test-uid';

    /**
     * @var string
     */
    private $workloadValue = 'test-workload';

    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @{inheritdoc}
     */
    protected function setUp()
    {
        $this->tableName = 'test_table';
        $this->dateTime = new \DateTime();
        $this->connection = $this->getMemoryConnection();
        $this->repository = new Database($this->connection, $this->tableName);
    }

    public function testSuccessfulConstruction()
    {
        static::assertInstanceOf('Bytepark\Component\Migration\Repository\Database', $this->repository);
    }

    public function testHistoryTableIsPresentAfterConstruction()
    {
        $tableName = 'test_table_not_existent';
        $connection = $this->getMemoryConnection();
        new Database($connection, $tableName);
        static::assertTrue($connection->execute(sprintf('SELECT * FROM %s;', $tableName)));
    }

    public function testWorkloadAddedOnConstruction()
    {
        $connection = $this->getFileConnection();
        $repository = new Database($connection, $this->tableName);

        $dbCount = $connection->query(
            sprintf('SELECT COUNT(id) AS cnt FROM %s', $this->tableName)
        );
        static::assertEquals((int) $dbCount[0]['cnt'], $repository->count());
    }

    public function testPersisting()
    {
        $unitOfWorkToPersist = $this->buildUnitOfWork();
        $this->repository->add($unitOfWorkToPersist);
        $wasSuccess = $this->repository->persist();

        $result = $this->connection->query(
            sprintf('SELECT * FROM %s', $this->tableName)
        );

        static::assertTrue($wasSuccess);
        static::assertEquals($this->repository->count(), count($result));
        static::assertEquals($this->uidValue, $result[0]['unique_id']);
        static::assertEquals($this->workloadValue, $result[0]['query']);
        static::assertEquals($this->dateTime->format('Y-m-d H:i:s'), $result[0]['migrated_at']);
    }

    public function testPersistingProducesNoDuplicates()
    {
        $unitOfWorkToPersist = $this->buildUnitOfWork();
        $this->repository->add($unitOfWorkToPersist);

        $firstPersistingWasSuccessful = $this->repository->persist();
        static::assertTrue($firstPersistingWasSuccessful);

        $secondPersistingWasSuccessful = $this->repository->persist();
        static::assertTrue($secondPersistingWasSuccessful);

        $dbCount = $this->connection->query(
            sprintf('SELECT count(unique_id) AS cnt FROM %s', $this->tableName)
        );

        static::assertEquals((int) $dbCount[0]['cnt'], $this->repository->count());
    }

    public function testReplace()
    {
        $unitToReplace = $this->buildUnitOfWork();
        $replacementUnit = $this->buildUnitOfWork('replaced!');
        $this->repository->add($unitToReplace);
        $this->repository->persist();
        $this->repository->replace($replacementUnit);
        $this->repository->persist();

        $result = $this->connection->query(
            sprintf('SELECT * FROM %s', $this->tableName)
        );

        static::assertEquals($this->uidValue, $result[0]['unique_id']);
        static::assertEquals('replaced!', $result[0]['query']);
    }

    protected function getMemoryConnection()
    {
        return new PdoSqliteMemory();
    }

    protected function getFileConnection()
    {
        $dbFile = new \SplFileInfo(TEST_DB_FIXTURE_FILE);

        return  new PdoSqliteFile($dbFile);
    }

    private function buildUnitOfWork($optionalWorkload = '')
    {
        if ('' === $optionalWorkload) {
            $optionalWorkload = $this->workloadValue;
        }

        $unitOfWork = new UnitOfWork(
            new Uid($this->uidValue),
            new Workload($optionalWorkload),
            $this->dateTime
        );

        return $unitOfWork;
    }
}
