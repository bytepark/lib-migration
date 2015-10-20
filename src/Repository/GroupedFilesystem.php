<?php
/**
* Class file of bytepark database migration Toolkit filesystem repository
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

use Bytepark\Component\Migration\Factory\UnitOfWorkFactory;
use Bytepark\Component\Migration\Repository;

/**
* Filesystem repository
*
* The repository scans the filesystem for migrations in a grouped
* subdirectory structure. The subdirectories are generated from the
* unit of works uid.
*
* @category   Bytepark
* @package    Migration
* @subpackage Repository
* @author     bytepark GmbH <code@bytepark.de>
* @license    http://www.bytepark.de proprietary
* @link       http://www.bytepark.de
*/
class GroupedFilesystem extends AbstractRepository
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var string
     */
    private $extension;

    /**
     * Instantiates the repository for the underlying file system
     *
     * A file system iterator has to be injected.
     *
     * The default file extension that will be included in the scan is "mig".
     *
     * @param \FilesystemIterator $directory The iterator to use
     * @param string              $extension The file extension to look for
     *
     * @throws \Bytepark\Component\Migration\Exception\UnitIsAlreadyPresentException
     * @throws \InvalidArgumentException
     */
    public function __construct(\FilesystemIterator $directory, $extension = 'mig')
    {
        $this->basePath = $directory->getPath();
        $this->extension = $extension;
        $this->buildMigrations($directory);
    }

    /**
     * @{inheritdoc}
     */
    public function persist()
    {
        foreach ($this as $fileName => $unitOfWork) {
            $filePath = $this->buildFilePath($fileName);
            $file = new \SplFileObject($filePath, 'w');

            $file->fwrite($unitOfWork->getQuery());
        }

        return true;
    }

    /**
     * Builds the migrations from the file system
     *
     * @param \FilesystemIterator $directory
     *
     * @throws \Bytepark\Component\Migration\Exception\UnitIsAlreadyPresentException
     * @throws \InvalidArgumentException
     */
    private function buildMigrations(\FilesystemIterator $directory)
    {
        foreach ($directory as $fileInfo) {
            /* @var $fileInfo \SplFileInfo */
            if ($fileInfo->isDir()) {
                $subDirectory = new \FilesystemIterator($fileInfo->getRealPath());
                $this->buildFromSubDirectory($subDirectory);
            }
        }
    }

    /**
     * Generates a subdirectory path for the given file
     *
     * @param string $fileName
     *
     * @return string
     */
    private function buildFilePath($fileName)
    {
        $digest = md5($fileName);

        $path = $this->basePath . '/' . substr($digest, 0, 1) . '/' . substr($digest, 1, 1);

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return $path . '/' . $fileName;
    }

    /**
     * Recursive build of the migrations
     *
     * Recursion ends when files are located in the given filesystem iterator.
     * Otherwise the recursion steps into the directory.
     *
     * @param \FilesystemIterator $directory The directory to build from
     *
     * @throws \Bytepark\Component\Migration\Exception\UnitIsAlreadyPresentException
     * @throws \InvalidArgumentException
     */

    private function buildFromSubDirectory(\FilesystemIterator $directory)
    {
        /* @var $fileInfo \SplFileInfo */
        foreach ($directory as $fileInfo) {
            if ($fileInfo->isDir()) {
                $subDirectory = new \FilesystemIterator($fileInfo->getRealPath());
                $this->buildFromSubDirectory($subDirectory);
            } elseif ($fileInfo->isFile() && $fileInfo->getExtension() === $this->extension) {
                $migration = UnitOfWorkFactory::buildFromSplFileInfo($fileInfo);
                $this->add($migration);
            }
        }
    }
}
