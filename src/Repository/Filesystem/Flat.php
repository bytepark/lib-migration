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

namespace Bytepark\Component\Migration\Repository\Filesystem;

use Bytepark\Component\Migration\Repository\AbstractFilesystem;
use Bytepark\Component\Migration\Factory\UnitOfWorkFactory;

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
class Flat extends AbstractFilesystem
{
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
     * @throws \InvalidArgumentException
     *
     * @param \FilesystemIterator $directory
     */
    protected function buildMigrations(\FilesystemIterator $directory)
    {
        foreach ($directory as $fileInfo) {
            /* @var $fileInfo \SplFileInfo */
            if ($fileInfo->isFile() && $fileInfo->getExtension() === $this->extension) {
                $migration = UnitOfWorkFactory::buildFromSplFileInfo($fileInfo);
                $this->add($migration);
            }
        }
    }
}
