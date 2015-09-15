<?php
/**
 * Class file of bytepark database migration Toolkit unit of work unique identifier
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
 * UnitOfWork unique identifier of the bytepark database migration Toolkit
 *
 * Implements a migration unit of work unique identifier
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage UnitOfWork
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class Uid
{
    /**
     * @var string
     */
    private $uid;

    /**
     * Instantiates a new unique identifier
     *
     * @param string $uniqueId The unique identifier of the migration
     *
     * @throws \InvalidArgumentException On missing guard
     */
    public function __construct($uniqueId)
    {
        $this->uid = $uniqueId;
        $this->guardUniqueIdIsNonEmptyString();
    }

    /**
     * Whether the given Uid equals this one
     *
     * @param Uid $other The Uid to compare for equality
     *
     * @return boolean true, if the uid values equal, false otherwise
     */
    public function equals(Uid $other)
    {
        return $this->uid === $other->uid;
    }

    /**
     * Textual representation of the Uid
     *
     * @return string Unique Identifier
     */
    public function __toString()
    {
        return $this->uid;
    }

    /**
     * Guards the uid property
     *
     * @throws \InvalidArgumentException On no or empty string
     */
    private function guardUniqueIdIsNonEmptyString()
    {
        if (!is_string($this->uid) || 0 === strlen(trim($this->uid))) {
            throw new \InvalidArgumentException(
                'The UID value has to be a non empty string'
            );
        }
    }
}
