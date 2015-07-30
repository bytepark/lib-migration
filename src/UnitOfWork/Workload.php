<?php
/**
 * Class file of bytepark database migration Toolkit unit of work workload
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

namespace Bytepark\Component\Migration\UnitOfWork;

/**
 * UnitOfWork workload of the bytepark database migration Toolkit
 *
 * Implements a migration unit of work workload
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage UnitOfWork
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class Workload
{
    /**
     * @var string
     */
    private $workload;

    /**
     * Instantiates a new migration
     *
     * @param string $workload The workload of the migration
     *
     * @throws InvalidArgumentException On missing guards
     */
    public function __construct($workload)
    {
        $this->workload = $workload;
        $this->guardWorkloadIsNonEmptyString();
    }

    /**
     * Textual representation of the workload
     *
     * The query can but has not be equivalent to the workload
     *
     * @return string The query to migrate
     */
    public function __toString()
    {
        return $this->workload;
    }

    /**
     * Guards the workload property
     *
     * @throws \InvalidArgumentException On no or empty string
     */
    private function guardWorkloadIsNonEmptyString()
    {
        if (!is_string($this->workload) || 0 === strlen(trim($this->workload))) {
            throw new \InvalidArgumentException(
                sprintf(
                    '"%s" is not valid workload value',
                    $this->workload
                )
            );
        }
    }
}
