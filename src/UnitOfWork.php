<?php
/**
* Class file of bytepark database migration Toolkit unit of work
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
* PHP version 5.3
*
* @category   Bytepark
* @package    Migration
* @subpackage UnitOfWork
* @author     bytepark GmbH <code@bytepark.de>
* @copyright  2014 - bytepark GmbH
* @license    http://www.bytepark.de proprietary
* @link       http://www.bytepark.de
*/

namespace Bytepark\Component\Migration;

use Bytepark\Component\Migration\UnitOfWork\Uid;
use Bytepark\Component\Migration\UnitOfWork\Workload;

/**
* UnitOfWork of the bytepark database migration Toolkit
*
* Implements a migration unit of work
*
* @category   Bytepark
* @package    Migration
* @subpackage UnitOfWork
* @author     bytepark GmbH <code@bytepark.de>
* @license    http://www.bytepark.de proprietary
* @link       http://www.bytepark.de
*/
class UnitOfWork
{
    /**
     * @var Uid
     */
    private $uniqueId;

    /**
     * @var Workload
     */
    private $workload;

    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * Instantiates a new migration work unit
     *
     * @param Uid       $uid      The unique identifier of the unit of work
     * @param Workload  $workload The workload of the unit of work
     * @param \DateTime $dateTime The date and time of the unit of work
     *
     * @throws \InvalidArgumentException On missing guards
     */
    public function __construct(Uid $uid, Workload $workload, \DateTime $dateTime)
    {
        $this->uniqueId = $uid;
        $this->workload = $workload;
        $this->dateTime = $dateTime;
    }

    /**
     * Getter for the unique identifier
     *
     * @return string Unique Identifier
     */
    public function getUniqueId()
    {
        return (string) $this->uniqueId;
    }

    /**
     * Getter for the query of the migration
     *
     * The query can but has not be equivalent to the workload
     *
     * @return string The query to migrate
     */
    public function getQuery()
    {
        return (string) $this->workload;
    }

    /**
     * Getter for the data time of the migration
     *
     * @return \DateTime
     */
    public function getMigrationDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Merges this unit with given one
     *
     * @throws \InvalidArgumentException
     *
     * @param UnitOfWork $other
     */
    public function merge(UnitOfWork $other)
    {
        if (!$this->uniqueId->equals($other->uniqueId)) {
            throw new \InvalidArgumentException(
                'Cannot merge units with different UIDs'
            );
        }

        $this->workload = new Workload(
            sprintf(
                '%s%s',
                $this->getQuery(),
                $other->getQuery()
            )
        );
    }

    /**
     * Migrates the unit of work into the database
     *
     * @throws \Bytepark\Component\Migration\Exception\QueryNotSuccessfulException
     *
     * @param Connection $connection The connection to migrate to
     */
    public function migrate(Connection $connection)
    {
        $connection->execute($this->getQuery());
    }
}
