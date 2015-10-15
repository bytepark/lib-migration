<?php
/**
 * Class file of Workload test cases
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

namespace Bytepark\Component\Migration\Test\UnnitOfWork;

use Bytepark\Component\Migration\UnitOfWork\Workload;

/**
 * Test cases for the Workload
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class WorkloadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Workload
     */
    protected $workload;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->workload = new Workload('my-workload');
    }

    public function testSuccessfulConstruction()
    {
        static::assertInstanceOf('Bytepark\Component\Migration\UnitOfWork\Workload', $this->workload);
        static::assertEquals('my-workload', (string) $this->workload);
    }

    public function testConstructionGuardDeniesInvalidWorkload()
    {
        $this->setExpectedException('\InvalidArgumentException');

        new Workload(null);
    }
}
