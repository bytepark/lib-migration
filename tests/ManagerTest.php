<?php
/**
 * Class file of Manager test cases
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

namespace Bytepark\Component\Migration\Test;

use Bytepark\Component\Migration\Lock\Filesystem;
use Bytepark\Component\Migration\Manager;
use Bytepark\Component\Migration\Connection;
use Bytepark\Component\Migration\Connection\PdoSqliteMemory;
use Bytepark\Component\Migration\Repository\Memory as MemoryRepository;
use Bytepark\Component\Migration\UnitOfWork;
use Bytepark\Component\Migration\UnitOfWork\Uid;
use Bytepark\Component\Migration\UnitOfWork\Workload;
use Bytepark\Component\Migration\Lock\Filesystem as FilesystemLock;

/**
 * Test cases for the Manager
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var Repository
     */
    protected $sourceRepository;

    /**
     * @var Repository
     */
    protected $historyRepository;

    /**
     * @var Lock
     */
    protected $lock;

    /**
     * @var Manager
     */
    protected $manager;

    protected function setUp()
    {
        $this->connection = new PdoSqliteMemory();
        $this->sourceRepository = new MemoryRepository();
        $this->historyRepository = new MemoryRepository();
        $this->lock = new FilesystemLock(new \SplFileInfo(TEST_LOCK_FILE));

        $this->manager = new Manager(
            $this->connection,
            $this->sourceRepository,
            $this->historyRepository,
            $this->lock
        );
    }

    public function tearDown()
    {
        if (is_file(TEST_LOCK_FILE)) {
            unlink(TEST_LOCK_FILE);
        }
    }

    public function testManagerConstruction()
    {
        $this->assertInstanceOf('Bytepark\Component\Migration\Manager', $this->manager);
    }

    public function testDispatchRepositoryCounts()
    {
        $this->sourceRepository->add($this->buildUnitOfWork());
        $this->assertEquals(1, $this->sourceRepository->count());
        $this->assertEquals(0, $this->historyRepository->count());
        $this->manager->dispatch();
        $this->assertEquals(1, $this->sourceRepository->count());
        $this->assertEquals(1, $this->historyRepository->count());
    }

    public function testDispatchWithActiveGuard()
    {
        $this->sourceRepository->add($this->buildUnitOfWork());
        $this->historyRepository->add($this->buildUnitOfWork());
        $this->historyRepository->add($this->buildUnitOfWork('2'));

        $this->setExpectedException('\Bytepark\Component\Migration\Exception\HistoryHasSourceUnknownUnitsOfWorkException');
        $this->manager->dispatch();
    }

    protected function buildUnitOfWork($uniqueIdValue = '1')
    {
        $uid = new Uid($uniqueIdValue);
        $workload = new Workload('CREATE TABLE test (id INTEGER PRIMARY KEY); DROP TABLE test;');
        $dateTime = new \DateTime();
        $unitOfWork = new UnitOfWork($uid, $workload, $dateTime);

        return $unitOfWork;
    }
}
