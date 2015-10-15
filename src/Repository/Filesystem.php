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
* The repository scans the filesystem for migrations in a flat directory
*
* @category   Bytepark
* @package    Migration
* @subpackage Repository
* @author     bytepark GmbH <code@bytepark.de>
* @license    http://www.bytepark.de proprietary
* @link       http://www.bytepark.de
*/
class Filesystem extends AbstractRepository
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * Instantiates the repository for the underlying file system
     *
     * A file system iterator has to be injected.
     *
     * @param \FilesystemIterator $directory The iterator to use
     *
     * @throws \Bytepark\Component\Migration\Exception\UnitIsAlreadyPresentException
     */
    public function __construct(\FilesystemIterator $directory)
    {
        $this->basePath = $directory->getPath();
        $this->buildMigrations($directory);
    }

    /**
     * @{inheritdoc}
     */
    public function persist()
    {
        foreach ($this as $fileName => $unitOfWork) {
            $file = new \SplFileObject($this->basePath . '/' . $fileName, 'w');

            $file->fwrite($unitOfWork->getQuery());
        }

        return true;
    }

    /**
     * Builds the migrations from the file system
     *
     * @throws \Bytepark\Component\Migration\Exception\UnitIsAlreadyPresentException
     *
     * @param \FilesystemIterator $directory
     */
    private function buildMigrations(\FilesystemIterator $directory)
    {
        foreach ($directory as $fileInfo) {
            /* @var $fileInfo \SplFileInfo */
            $migration = UnitOfWorkFactory::buildFromSplFileInfo($fileInfo);
            $this->add($migration);
        }
    }
}
