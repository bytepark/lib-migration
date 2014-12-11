<?php
/**
 * Class file of bytepark database migration Toolkit abstract repository
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

use Bytepark\Component\Migration\Exception\UnitIsAlreadyPresentException;
use Bytepark\Component\Migration\Exception\UnitNotFoundException;
use Bytepark\Component\Migration\Exception\UnitNotPresentException;
use Bytepark\Component\Migration\Repository;
use Bytepark\Component\Migration\UnitOfWork;
use Bytepark\Component\Migration\UnitOfWork\Uid;

/**
 * Abstract repository
 *
 * The repository implements the extended interfaces \Countable and \Iterator
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Repository
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
abstract class AbstractRepository implements Repository
{
    /**
     * @var UnitOfWork[]
     */
    private $unitOfWorkStore = array();

    /**
     * @{inheritdoc}
     */
    public function diff(Repository $otherRepository)
    {
        $diffRepository = new Memory();

        foreach ($this->unitOfWorkStore as $unitOfWork) {
            if (!$otherRepository->contains($unitOfWork)) {
                $diffRepository->add($unitOfWork);
            }
        }

        return $diffRepository;
    }

    /**
     * Finds the unit with the given Uid
     *
     * @param Uid $uniqueId The Uid of the unit to find
     *
     * @throws UnitNotFoundException
     *
     * @return UnitOfWork
     */
    public function find(Uid $uniqueId)
    {
        if (!isset($this->unitOfWorkStore[$uniqueId->__toString()])) {
            throw new UnitNotFoundException();
        }

        return $this->unitOfWorkStore[$uniqueId->__toString()];
    }

    /**
     * Adds a UnitOfWork to the repository
     *
     * @param UnitOfWork $unitOfWork The migration to add
     *
     * @throws UnitIsAlreadyPresentException
     *
     * @return void
     */
    public function add(UnitOfWork $unitOfWork)
    {
        if ($this->contains($unitOfWork)) {
            throw new UnitIsAlreadyPresentException();
        }

        $this->unitOfWorkStore[$unitOfWork->getUniqueId()] = $unitOfWork;
    }

    /**
     * Replaces with the given unit of work
     *
     * @param UnitOfWork $unitOfWork The unit of work to replace with
     *
     * @throws UnitNotPresentException
     *
     * @return void
     */
    public function replace(UnitOfWork $unitOfWork)
    {
        if (!$this->contains($unitOfWork)) {
            throw new UnitNotPresentException;
        }

        $this->unitOfWorkStore[$unitOfWork->getUniqueId()] = $unitOfWork;
    }

    /**
     * @{inheritdoc}
     */
    public function rewind()
    {
        return reset($this->unitOfWorkStore);
    }

    /**
     * @{inheritdoc}
     */
    public function current()
    {
        return current($this->unitOfWorkStore);
    }

    /**
     * @{inheritdoc}
     */
    public function key()
    {
        return key($this->unitOfWorkStore);
    }

    /**
     * @{inheritdoc}
     */
    public function next()
    {
        return next($this->unitOfWorkStore);
    }

    /**
     * @{inheritdoc}
     */
    public function valid()
    {
        return !is_null(key($this->unitOfWorkStore));
    }

    /**
     * @{inheritdoc}
     */
    public function count()
    {
        return count($this->unitOfWorkStore);
    }

    /**
     * @{inheritdoc}
     *
     * @param UnitOfWork $unitOfWork The unit to check
     *
     * @return boolean Whether the given unit is in the repository
     */
    public function contains(UnitOfWork $unitOfWork)
    {
        return isset($this->unitOfWorkStore[$unitOfWork->getUniqueId()]);
    }
}
