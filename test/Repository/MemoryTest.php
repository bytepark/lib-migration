<?php
/**
 * Class file of Memory repository test cases
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

namespace Bytepark\Component\Migration\Test\Repository;

use Bytepark\Component\Migration\Repository\Memory;
use Bytepark\Component\Migration\UnitOfWork;

/**
 * Test cases for the memory repository
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class MemoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Bytepark\Component\Migration\Repository
     */
    protected $repository;

    /**
     * @{inheritdoc}
     */
    protected function setUp()
    {
        $this->repository = new Memory();
    }

    public function testSuccessfulConstruction()
    {
        static::assertInstanceOf('Bytepark\Component\Migration\Repository\Memory', $this->repository);
    }

    public function testInitialMemoryRepositoryIsEmpty()
    {
        static::assertEquals(0, $this->repository->count());
    }

    public function testPersisting()
    {
        static::assertTrue($this->repository->persist());
    }
}
