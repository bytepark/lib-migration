<?php
/**
* Class file of bytepark database migration Toolkit repository interface
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

namespace Bytepark\Component\Migration;

use Bytepark\Component\Migration\UnitOfWork\Uid;

/**
* Repository interface for migrations
*
* @category   Bytepark
* @package    Migration
* @subpackage Repository
* @author     bytepark GmbH <code@bytepark.de>
* @license    http://www.bytepark.de proprietary
* @link       http://www.bytepark.de
*/
interface Repository extends \Countable, \Iterator
{
    /**
     * Calculates the diff to the given repository
     *
     * The method returns a Repository including all Migrations present in this
     * instance but not the given other repository.
     *
     * @param Repository $otherRepository The repository to diff to
     *
     * @throws \Bytepark\Component\Migration\Exception\UnitIsAlreadyPresentException
     *
     * @return Repository The diff
     */
    public function diff(Repository $otherRepository);

    /**
     * Persists the repository into the underlying storage
     *
     * @return boolean Whether the method was executed successful
     */
    public function persist();

    /**
     * Finds the unit with the given Uid
     *
     * @param Uid $uniqueId The Uid of the unit to find
     *
     * @throws \Bytepark\Component\Migration\Exception\UnitNotFoundException
     *
     * @return UnitOfWork
     */
    public function find(Uid $uniqueId);

    /**
     * Adds the given unit of work to the repository
     *
     * @param UnitOfWork $unitOfWork The unit of work to add
     *
     * @throws \Bytepark\Component\Migration\Exception\UnitIsAlreadyPresentException
     *
     * @return void
     */
    public function add(UnitOfWork $unitOfWork);

    /**
     * Replaces with the given unit of work
     *
     * @param UnitOfWork $unitOfWork The unit of work to replace with
     *
     * @throws \Bytepark\Component\Migration\Exception\UnitNotPresentException
     *
     * @return void
     */
    public function replace(UnitOfWork $unitOfWork);

    /**
     * Whether the repository contains the given unit of work
     *
     * @param UnitOfWork $unitOfWork The needle
     *
     * @return boolean Whether the repository contains the given unit of work
     */
    public function contains(UnitOfWork $unitOfWork);
}
