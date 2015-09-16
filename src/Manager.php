<?php
/**
 * Class file of bytepark database migration Toolkit
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Manager
 * @author     bytepark GmbH <code@bytepark.de>
 * @copyright  2014 - bytepark GmbH
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */

namespace Bytepark\Component\Migration;

use Bytepark\Component\Migration\Connection;
use Bytepark\Component\Migration\Exception\QueryNotSuccessfulException;
use Bytepark\Component\Migration\Repository;
use Bytepark\Component\Migration\Lock;
use Bytepark\Component\Migration\Exception\HistoryHasSourceUnknownUnitsOfWorkException;

/**
 * Base class of bytepark database migration Toolkit
 *
 * This class is the entry point of the migration toolkit.
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Manager
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class Manager
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Repository
     */
    private $sourceRepository;

    /**
     * @var Repository
     */
    private $historyRepository;

    /**
     * @var Lock
     */
    private $lock;

    /**
     * The default constructor to setup base data for migrations
     *
     * @param Connection $connection        The connection used by the manager
     * @param Repository $sourceRepository  The repository with the source migrations
     * @param Repository $historyRepository The repository with the executed migrations
     * @param Lock       $lock              The lock for atomicity
     */
    public function __construct(
        Connection $connection,
        Repository $sourceRepository,
        Repository $historyRepository,
        Lock $lock
    ) {
        $this->connection = $connection;
        $this->sourceRepository = $sourceRepository;
        $this->historyRepository = $historyRepository;
        $this->lock = $lock;
    }

    /**
     * Dispatches the migration process
     *
     * @return void
     */
    public function dispatch()
    {
        $this->lock->acquire();

        $this->guardThatHistoryRepositoryDoesNotHaveMoreUnitsOfWork();

        $workloadRepository = $this->sourceRepository->diff(
            $this->historyRepository
        );

        foreach ($workloadRepository as $unitOfWork) {
            $this->executeMigration($unitOfWork);
        }

        $this->historyRepository->persist();

        $this->lock->release();
    }

    /**
     * Guards the there are no units of work in the history repository that are not
     * also in the source repository
     *
     * @throws Exception\HistoryHasSourceUnknownUnitsOfWorkException
     */
    private function guardThatHistoryRepositoryDoesNotHaveMoreUnitsOfWork()
    {
        $historyDiffRepository = $this->historyRepository->diff(
            $this->sourceRepository
        );

        if (0 < $historyDiffRepository->count()) {
            throw new HistoryHasSourceUnknownUnitsOfWorkException();
        }
    }

    /**
     * Executes the given migration against the database connection
     *
     * @param UnitOfWork $unitOfWork
     */
    private function executeMigration(UnitOfWork $unitOfWork)
    {
        try {
            $unitOfWork->migrate($this->connection);
            $this->historyRepository->add($unitOfWork);
        } catch (QueryNotSuccessfulException $e) {
            // for now do nothing  and leave out of history
        }
    }
}
