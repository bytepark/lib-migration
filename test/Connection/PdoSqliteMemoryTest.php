<?php
/**
 * Class file of pdo sqlite memory connection test cases
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

namespace Bytepark\Component\Migration\Test\Connection;

use Bytepark\Component\Migration\Connection\PdoSqliteMemory;

/**
 * Test cases for the pdo sqlite memory connection
 *
 * @category   Bytepark
 * @package    Migration
 * @subpackage Test
 * @author     bytepark GmbH <code@bytepark.de>
 * @license    http://www.bytepark.de proprietary
 * @link       http://www.bytepark.de
 */
class PdoSqliteMemoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    protected $connection;

    protected function setUp()
    {
        $this->connection = new PdoSqliteMemory();
    }

    public function testSuccessfulConstruction()
    {
        $this->assertInstanceOf('Bytepark\Component\Migration\Connection\PdoSqliteMemory', $this->connection);
    }

    public function testSuccessfulExecution()
    {
        $this->assertTrue($this->connection->execute('CREATE TABLE IF NOT EXISTS test_table (id INTEGER PRIMARY KEY);)'));
        $this->assertTrue($this->connection->execute('INSERT INTO test_table VALUES(NULL);'));
        $this->assertTrue($this->connection->execute('SELECT * FROM test_table WHERE id = 1;'));
    }

    public function testUnsuccessfulExecution()
    {
        $this->setExpectedException('Bytepark\Component\Migration\Exception\QueryNotSuccessfulException');

        $this->assertFalse($this->connection->execute('CREATE TABLE;)'));
    }

    public function testSuccessfulQuery()
    {
        $this->prepareValidDatabaseTable();
        $queryResult = $this->connection->query('SELECT * FROM test_table WHERE id = 1;');
        $this->assertArrayHasKey(0, $queryResult);
        $this->assertEquals(array('id' => '1'), $queryResult[0]);
    }

    public function testEmptyQuery()
    {
        $this->prepareEmptyDatabaseTable();
        $queryResult = $this->connection->query('SELECT * FROM test_table WHERE id = 1;');
        $this->assertArrayNotHasKey(0, $queryResult);
        $this->assertEquals(array(), $queryResult);
    }

    public function testUnsuccessfulQuery()
    {
        $this->setExpectedException('Bytepark\Component\Migration\Exception\QueryNotSuccessfulException');

        $this->prepareEmptyDatabaseTable();
        $queryResult = $this->connection->query('SELECT WHERE id = 1;');
    }

    private function prepareValidDatabaseTable()
    {
        $this->connection->execute('CREATE TABLE IF NOT EXISTS test_table (id INTEGER PRIMARY KEY);)');
        $this->connection->execute('INSERT INTO test_table VALUES(NULL);');
        $this->connection->execute('SELECT * FROM test_table WHERE id = 1;');
    }

    private function prepareEmptyDatabaseTable()
    {
        $this->connection->execute('CREATE TABLE IF NOT EXISTS test_table (id INTEGER PRIMARY KEY);)');
    }
}
