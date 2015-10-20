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
abstract class AbstractFilesystem extends AbstractRepository
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    protected $extension;

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
    abstract public function persist();

    /**
     * Builds the migrations from the file system
     *
     * @throws \Bytepark\Component\Migration\Exception\UnitIsAlreadyPresentException
     * @throws \InvalidArgumentException
     *
     * @param \FilesystemIterator $directory
     */
    abstract protected function buildMigrations(\FilesystemIterator $directory);
}
