<?php
/**
 * Class file of GroupedFilesystem test cases
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

use Bytepark\Component\Migration\Repository\GroupedFilesystem;
use Bytepark\Component\Migration\UnitOfWork;
use Bytepark\Component\Migration\UnitOfWork\Uid;
use Bytepark\Component\Migration\UnitOfWork\Workload;
/**
 * Test cases for the grouped filesystem repository
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class GroupedFilesystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FilesystemIterator
     */
    protected $filesystemIterator;

    /**
     * @var \Bytepark\Component\Migration\Repository
     */
    protected $repository;

    /**
     * @{inheritdoc}
     */
    protected function setUp()
    {
        $this->filesystemIterator = new \FilesystemIterator(TEST_GROUPED_FILE_FIXTURE_PATH);
        $this->repository = new GroupedFilesystem($this->filesystemIterator);
    }

    /**
     * @{inheritdoc}
     */
    protected function tearDown()
    {
        $fileName = 'persisting-test.mig';
        $filePath = TEST_GROUPED_FILE_FIXTURE_PATH . '/b/d/' . $fileName;

        if (is_file($filePath)) {
            unlink($filePath);
            rmdir(TEST_GROUPED_FILE_FIXTURE_PATH . '/b/d');
            rmdir(TEST_GROUPED_FILE_FIXTURE_PATH . '/b');
        }

    }

    public function testSuccessfulConstruction()
    {
        static::assertInstanceOf('Bytepark\Component\Migration\Repository\GroupedFilesystem', $this->repository);
    }

    public function testFilesAreLoadedOnConstruction()
    {
        static::assertEquals(count($this->filesystemIterator), $this->repository->count());
    }

    public function testWorkloadsMatchFileContents()
    {
        foreach ($this->repository as $uid => $unitOfWork) {
            /* @var $unitOfWork UnitOfWork */
            $fileName = TEST_FILE_FIXTURE_PATH . '/' . $uid;
            static::assertEquals(file_get_contents($fileName), $unitOfWork->getQuery());
        }
    }

    public function testPersisting()
    {
        $fileName = 'persisting-test.mig';
        $fileContent = 'file-contents';
        $filePath = TEST_GROUPED_FILE_FIXTURE_PATH . '/b/d/' . $fileName;

        $unitOfWorkToPersist = new UnitOfWork(
            new Uid($fileName),
            new Workload($fileContent),
            new \DateTime()
        );

        $this->repository->add($unitOfWorkToPersist);
        $wasSuccess = $this->repository->persist();

        static::assertTrue($wasSuccess);
        static::assertFileExists($filePath);
        static::assertEquals($fileContent, file_get_contents($filePath));
    }
}
