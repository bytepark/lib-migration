<?php
/**
 * Class file of bytepark database migration Toolkit migration factory
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Factory
 * @author     bytepark GmbH <code@bytepark.de>
 * @copyright  2014 - bytepark GmbH
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */

namespace Bytepark\Component\Migration\Factory;

use Bytepark\Component\Migration\UnitOfWork;
use Bytepark\Component\Migration\UnitOfWork\Uid;
use Bytepark\Component\Migration\UnitOfWork\Workload;

/**
 * Factory for building UnitOfWork objects
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Factory
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class UnitOfWorkFactory
{
    /**
     * Builds a UnitOfWork instance from the given \SplFileInfo
     *
     * @param \SplFileInfo $fileInfo The fileInfo to build from
     *
     * @return UnitOfWork The instantiated migration
     */
    public static function buildFromSplFileInfo(\SplFileInfo $fileInfo)
    {
        $uniqueId = $fileInfo->getBasename();
        $file = $fileInfo->openFile();
        $query = '';

        while (!$file->eof()) {
            $query .= $file->fgets();
        }

        return new UnitOfWork(new Uid($uniqueId), new Workload($query), new \DateTime());
    }

    /**
     * Builds a UnitOfWork instance from the given associative array
     *
     * The expected keys in the given array are:
     *
     * - unique_id: The value for the encapsulated Uid object
     * - query: The value for the encapsulated Workload object
     * - migrated_at: A valid datetime representation for the constructor of \DateTime
     *
     * @param array $values The values to build from
     *
     * @return UnitOfWork The instantiated migration
     */
    public static function buildFromFlatArray(array $values)
    {
        return new UnitOfWork(
            new Uid($values['unique_id']),
            new Workload($values['query']),
            new \DateTime($values['migrated_at'])
        );
    }
}
