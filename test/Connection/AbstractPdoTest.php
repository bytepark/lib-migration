<?php
/**
 * Class file of abstract pdo connection test cases
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

namespace Bytepark\Component\Migration\Test\Connection;

use Bytepark\Component\Migration\Connection\AbstractPdo;

/**
 * Test cases for the abstract pdo connection
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class AbstractPdoTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConnectionThrowsLogicException()
    {
        $connection = $this->getMockForAbstractClass('Bytepark\Component\Migration\Connection\AbstractPdo');
        $this->setExpectedException('\LogicException', 'Connection must be instantiated in the constructor');

        $connection->query('');
    }
}
