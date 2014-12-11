<?php
/**
 * Class file of UnitOfWork test cases
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

namespace Bytepark\Component\Migration\Test;

use Bytepark\Component\Migration\UnitOfWork;
use Bytepark\Component\Migration\UnitOfWork\Uid;
use Bytepark\Component\Migration\UnitOfWork\Workload;
use Zend\I18n\Validator\DateTime;

/**
 * Test cases for the UnitOfWork
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class UnitOfWorkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Uid
     */
    protected $uid;

    /**
     * @var Workload
     */
    protected $workload;

    /**
     * @var UnitOfWork
     */
    protected $unitOfWork;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->uid = new Uid('my-unique-identifier');
        $this->workload = new Workload('my-workload');

        $this->unitOfWork = $this->buildUnit();
    }

    public function testSuccessfulConstruction()
    {
        $this->assertInstanceOf('Bytepark\Component\Migration\UnitOfWork', $this->unitOfWork);
    }

    public function testMergeRaisesExceptionOnDifferentUid()
    {
        $unmergeableUnit = new UnitOfWork(new Uid('unmergable-uid'), $this->workload, new \DateTime());
        $this->setExpectedException('\InvalidArgumentException');
        $this->unitOfWork->merge($unmergeableUnit);
    }

    public function testMerge()
    {
        $this->unitOfWork->merge($this->buildUnit('-and-some-more-merged'));

        $this->assertEquals('my-workload-and-some-more-merged', $this->unitOfWork->getQuery());
    }

    private function buildUnit($optionalWorkload = '')
    {
        return new UnitOfWork(
            $this->uid,
            strlen($optionalWorkload) === 0 ? $this->workload : new Workload($optionalWorkload),
            new \DateTime()
        );

    }
}
?>
