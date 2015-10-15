<?php
/**
 * Bootstrap file for testing environment
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

define('TEST_FILE_FIXTURE_PATH', __DIR__ . '/../Resources/fixtures');
define('TEST_GROUPED_FILE_FIXTURE_PATH', __DIR__ . '/../Resources/grouped_fixtures');
define('TEST_DB_FIXTURE_FILE', __DIR__ . '/../Resources/db-fixture.sqlite');
define('TEST_LOCK_FILE', __DIR__ . '/../Resources/.migration-lock');

$autoloadFile = __DIR__ . '/../vendor/autoload.php';

if (!is_file($autoloadFile)) {
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer update"?');
}

require $autoloadFile;
