<?php
/**
 * Class file of Filesystem test cases
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

namespace Bytepark\Component\Migration\Test\Repository\Filesystem;

use Bytepark\Component\Migration\Repository\Filesystem\Flat;
use Bytepark\Component\Migration\UnitOfWork;
use Bytepark\Component\Migration\UnitOfWork\Uid;
use Bytepark\Component\Migration\UnitOfWork\Workload;

/**
 * Test cases for the filesystem repository
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class FlatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FilesystemIterator
     */
    protected $source;

    /**
     * @var \Bytepark\Component\Migration\Repository
     */
    protected $repository;

    /**
     * @{inheritdoc}
     */
    protected function setUp()
    {
        $this->source = new \FilesystemIterator(TEST_FILE_FIXTURE_PATH);
        $this->repository = new Flat($this->source, 'mig');
    }

    /**
     * @{inheritdoc}
     */
    protected function tearDown()
    {
        $fileName = 'persisting-test.mig';
        $filePath = TEST_FILE_FIXTURE_PATH . '/' . $fileName;

        if (is_file($filePath)) {
            unlink($filePath);
        }

    }

    public function testSuccessfulConstruction()
    {
        static::assertInstanceOf('Bytepark\Component\Migration\Repository\Filesystem\Flat', $this->repository);
    }

    public function testFilesAreLoadedOnConstruction()
    {
        static::assertEquals(count(glob(TEST_FILE_FIXTURE_PATH.'/*.mig')), $this->repository->count());
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
        $filePath = TEST_FILE_FIXTURE_PATH . '/' . $fileName;


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

    public function testFindOnNonExistingUnitRaisesException()
    {
        $this->setExpectedException('Bytepark\Component\Migration\Exception\UnitNotFoundException');
        $this->repository->find(new Uid('persisting-test.mig'));
    }

    public function testFind()
    {
        $unit = $this->buildUnitOfWork();
        $this->repository->add($unit);
        $unitFromRepo = $this->repository->find(new Uid('persisting-test.mig'));

        static::assertEquals($unitFromRepo, $unit);
    }

    public function testMultipleAddRaisesException()
    {
        $unit = $this->buildUnitOfWork();
        $this->repository->add($unit);
        $this->setExpectedException('Bytepark\Component\Migration\Exception\UnitIsAlreadyPresentException');
        $this->repository->add($unit);
    }

    public function testReplaceOnNonPresentUnitRaisesException()
    {
        $unit = $this->buildUnitOfWork();
        $this->setExpectedException('Bytepark\Component\Migration\Exception\UnitNotPresentException');
        $this->repository->replace($unit);
    }

    public function testReplace()
    {
        $filePath = TEST_FILE_FIXTURE_PATH . '/persisting-test.mig';
        $unitToReplace = $this->buildUnitOfWork();
        $replacementUnit = $this->buildUnitOfWork('replaced!');
        $this->repository->add($unitToReplace);
        $this->repository->persist();
        $this->repository->replace($replacementUnit);
        $this->repository->persist();

        static::assertEquals('replaced!', file_get_contents($filePath));
    }

    public function testNeverEverMigrateHiddenFiles()
    {

    }

    private function buildUnitOfWork($optionalWorkload = 'some test irrelevant workload')
    {
        return new UnitOfWork(
            new UnitOfWork\Uid('persisting-test.mig'),
            new UnitOfWork\Workload($optionalWorkload),
            new \DateTime()
        );
    }

}
