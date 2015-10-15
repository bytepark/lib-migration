<?php
/**
 * Class file of Uid test cases
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

namespace Bytepark\Component\Migration\Test\UnitOfWork;

use Bytepark\Component\Migration\UnitOfWork\Uid;

/**
 * Test cases for the Uid
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class UidTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Uid
     */
    protected $uid;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->uid = new Uid('my-unique-identifier');
    }

    public function testSuccessfulConstruction()
    {
        static::assertInstanceOf('Bytepark\Component\Migration\UnitOfWork\Uid', $this->uid);
        static::assertEquals('my-unique-identifier', (string) $this->uid);
    }

    public function testConstructionGuardDeniesInvalidUniqueId()
    {
        $this->setExpectedException('\InvalidArgumentException');

        new Uid(null);
    }

    public function testEqualsReturnsTrueForSameUid()
    {
        static::assertTrue($this->uid->equals($this->uid));
    }

    public function testEqualsReturnsFalseForDifferentUid()
    {
        static::assertFalse($this->uid->equals(new Uid('im-not-matching')));
    }
}
?>
