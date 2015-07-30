<?php
/**
 * Class file of bytepark database migration Toolkit memory repository
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

use Bytepark\Component\Migration\Repository;

/**
 * Memory repository
 *
 * The repository holds all migrations in memory
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Repository
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class Memory extends AbstractRepository
{
    /**
     * @{inheritdoc}
     */
    public function persist()
    {
        return true;
    }
}
