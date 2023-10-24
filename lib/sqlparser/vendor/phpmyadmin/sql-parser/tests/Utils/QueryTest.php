<?php

namespace PhpMyAdmin\SqlParser\Tests\Utils;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Utils\Query;

class QueryTest extends TestCase
{
    /**
     * @dataProvider getFlagsProvider
     *
     * @param mixed $query
     * @param mixed $expected
     */
    public function testGetFlags($query, $expected)
    {
        $parser = new Parser($query);
        $this->assertEquals(
            $expected,
            Query::getFlags($parser->statements[0])
        );
    }

    public function getFlagsProvider()
    {
        return array(
            array(
                'ALTER TABLE DROP col',
                array(
                    'reload' => true,
                    'querytype' => 'ALTER',
                ),
            ),
            array(
                'CALL test()',
                array(
                    'is_procedure' => true,
                    'querytype' => 'CALL',
                ),
            ),
            array(
                'CREATE TABLE tbl (id INT)',
                array(
                    'reload' => true,
                    'querytype' => 'CREATE',
                ),
            ),
            array(
                'CHECK TABLE tbl',
                array(
                    'is_maint' => true,
                    'querytype' => 'CHECK',
                ),
            ),
            array(
                'DELETE FROM tbl',
                array(
                    'is_affected' => true,
                    'is_delete' => true,
                    'querytype' => 'DELETE',
                ),
            ),
            array(
                'DROP VIEW v',
                array(
                    'reload' => true,
                    'querytype' => 'DROP',
                ),
            ),
            array(
                'DROP DATABASE db',
                array(
                    'drop_database' => true,
                    'reload' => true,
                    'querytype' => 'DROP',
                ),
            ),
            array(
                'EXPLAIN tbl',
                array(
                    'is_explain' => true,
                    'querytype' => 'EXPLAIN',
                ),
            ),
            array(
                'LOAD DATA INFILE \'/tmp/test.txt\' INTO TABLE test',
                array(
                    'is_affected' => true,
                    'is_insert' => true,
                    'querytype' => 'LOAD',
                ),
            ),
            array(
                'INSERT INTO tbl VALUES (1)',
                array(
                    'is_affected' => true,
                    'is_insert' => true,
                    'querytype' => 'INSERT',
                ),
            ),
            array(
                'REPLACE INTO tbl VALUES (2)',
                array(
                    'is_affected' => true,
                    'is_replace' => true,
                    'is_insert' => true,
                    'querytype' => 'REPLACE',
                ),
            ),
            array(
                'SELECT 1',
                array(
                    'is_select' => true,
                    'querytype' => 'SELECT',
                ),
            ),
            array(
                'SELECT * FROM tbl',
                array(
                    'is_select' => true,
                    'select_from' => true,
                    'querytype' => 'SELECT',
                ),
            ),
            array(
                'SELECT DISTINCT * FROM tbl LIMIT 0, 10 ORDER BY id',
                array(
                    'distinct' => true,
                    'is_select' => true,
                    'select_from' => true,
                    'limit' => true,
                    'order' => true,
                    'querytype' => 'SELECT',
                ),
            ),
            array(
                'SELECT * FROM actor GROUP BY actor_id',
                array(
                    'is_group' => true,
                    'is_select' => true,
                    'select_from' => true,
                    'group' => true,
                    'querytype' => 'SELECT',
                ),
            ),
            array(
                'SELECT col1, col2 FROM table1 PROCEDURE ANALYSE(10, 2000);',
                array(
                    'is_analyse' => true,
                    'is_select' => true,
                    'select_from' => true,
                    'querytype' => 'SELECT',
                ),
            ),
            array(
                'SELECT * FROM tbl INTO OUTFILE "/tmp/export.txt"',
                array(
                    'is_export' => true,
                    'is_select' => true,
                    'select_from' => true,
                    'querytype' => 'SELECT',
                ),
            ),
            array(
                'SELECT COUNT(id), SUM(id) FROM tbl',
                array(
                    'is_count' => true,
                    'is_func' => true,
                    'is_select' => true,
                    'select_from' => true,
                    'querytype' => 'SELECT',
                ),
            ),
            array(
                'SELECT (SELECT "foo")',
                array(
                    'is_select' => true,
                    'is_subquery' => true,
                    'querytype' => 'SELECT',
                ),
            ),
            array(
                'SELECT * FROM customer HAVING store_id = 2;',
                array(
                    'is_select' => true,
                    'select_from' => true,
                    'is_group' => true,
                    'having' => true,
                    'querytype' => 'SELECT',
                ),
            ),
            array(
                'SELECT * FROM table1 INNER JOIN table2 ON table1.id=table2.id;',
                array(
                    'is_select' => true,
                    'select_from' => true,
                    'join' => true,
                    'querytype' => 'SELECT',
                ),
            ),
            array(
                'SHOW CREATE TABLE tbl',
                array(
                    'is_show' => true,
                    'querytype' => 'SHOW',
                ),
            ),
            array(
                'UPDATE tbl SET id = 1',
                array(
                    'is_affected' => true,
                    'querytype' => 'UPDATE',
                ),
            ),
            array(
                'ANALYZE TABLE tbl',
                array(
                    'is_maint' => true,
                    'querytype' => 'ANALYZE',
                ),
            ),
            array(
                'CHECKSUM TABLE tbl',
                array(
                    'is_maint' => true,
                    'querytype' => 'CHECKSUM',
                ),
            ),
            array(
                'OPTIMIZE TABLE tbl',
                array(
                    'is_maint' => true,
                    'querytype' => 'OPTIMIZE',
                ),
            ),
            array(
                'REPAIR TABLE tbl',
                array(
                    'is_maint' => true,
                    'querytype' => 'REPAIR',
                ),
            ),
            array(
                '(SELECT a FROM t1 WHERE a=10 AND B=1 ORDER BY a LIMIT 10) ' .
                'UNION ' .
                '(SELECT a FROM t2 WHERE a=11 AND B=2 ORDER BY a LIMIT 10);',
                array(
                    'is_select' => true,
                    'select_from' => true,
                    'limit' => true,
                    'order' => true,
                    'union' => true,
                    'querytype' => 'SELECT',
                ),
            ),
            array(
                'SELECT * FROM orders AS ord WHERE 1',
                array(
                    'querytype' => 'SELECT',
                    'is_select' => true,
                    'select_from' => true,
                ),
            ),
            array(
                'SET NAMES \'latin\'',
                array(
                    'querytype' => 'SET',
                ),
            )
        );
    }

    public function testGetAll()
    {
        $this->assertEquals(
            array(
                'distinct' => false,
                'drop_database' => false,
                'group' => false,
                'having' => false,
                'is_affected' => false,
                'is_analyse' => false,
                'is_count' => false,
                'is_delete' => false,
                'is_explain' => false,
                'is_export' => false,
                'is_func' => false,
                'is_group' => false,
                'is_insert' => false,
                'is_maint' => false,
                'is_procedure' => false,
                'is_replace' => false,
                'is_select' => false,
                'is_show' => false,
                'is_subquery' => false,
                'join' => false,
                'limit' => false,
                'offset' => false,
                'order' => false,
                'querytype' => false,
                'reload' => false,
                'select_from' => false,
                'union' => false,
            ),
            Query::getAll('')
        );

        $query = 'SELECT *, actor.actor_id, sakila2.film.*
            FROM sakila2.city, sakila2.film, actor';
        $parser = new Parser($query);
        $this->assertEquals(
            array_merge(
                Query::getFlags($parser->statements[0], true),
                array(
                    'parser' => $parser,
                    'statement' => $parser->statements[0],
                    'select_expr' => array('*'),
                    'select_tables' => array(
                        array(
                            'actor',
                            null,
                        ),
                        array(
                            'film',
                            'sakila2',
                        ),
                    )
                )
            ),
            Query::getAll($query)
        );

        $query = 'SELECT * FROM sakila.actor, film';
        $parser = new Parser($query);
        $this->assertEquals(
            array_merge(
                Query::getFlags($parser->statements[0], true),
                array(
                    'parser' => $parser,
                    'statement' => $parser->statements[0],
                    'select_expr' => array('*'),
                    'select_tables' => array(
                        array(
                            'actor',
                            'sakila',
                        ),
                        array(
                            'film',
                            null,
                        ),
                    )
                )
            ),
            Query::getAll($query)
        );

        $query = 'SELECT a.actor_id FROM sakila.actor AS a, film';
        $parser = new Parser($query);
        $this->assertEquals(
            array_merge(
                Query::getFlags($parser->statements[0], true),
                array(
                    'parser' => $parser,
                    'statement' => $parser->statements[0],
                    'select_expr' => array(),
                    'select_tables' => array(
                        array(
                            'actor',
                            'sakila',
                        ),
                    ),
                )
            ),
            Query::getAll($query)
        );

        $query = 'SELECT CASE WHEN 2 IS NULL THEN "this is true" ELSE "this is false" END';
        $parser = new Parser($query);
        $this->assertEquals(
            array_merge(
                Query::getFlags($parser->statements[0], true),
                array(
                    'parser' => $parser,
                    'statement' => $parser->statements[0],
                    'select_expr' => array(
                        'CASE WHEN 2 IS NULL THEN "this is true" ELSE "this is false" END',
                    ),
                    'select_tables' => array(),
                )
            ),
            Query::getAll($query)
        );
    }

    /**
     * @dataProvider getTablesProvider
     *
     * @param mixed $query
     * @param mixed $expected
     */
    public function testGetTables($query, $expected)
    {
        $parser = new Parser($query);
        $this->assertEquals(
            $expected,
            Query::getTables($parser->statements[0])
        );
    }

    public function getTablesProvider()
    {
        return array(
            array(
                'INSERT INTO tbl(`id`, `name`) VALUES (1, "Name")',
                array('`tbl`')
            ),
            array(
                'INSERT INTO 0tbl(`id`, `name`) VALUES (1, "Name")',
                array('`0tbl`')
            ),
            array(
                'UPDATE tbl SET id = 0',
                array('`tbl`')
            ),
            array(
                'UPDATE 0tbl SET id = 0',
                array('`0tbl`')
            ),
            array(
                'DELETE FROM tbl WHERE id < 10',
                array('`tbl`')
            ),
            array(
                'DELETE FROM 0tbl WHERE id < 10',
                array('`0tbl`')
            ),
            array(
                'TRUNCATE tbl',
                array('`tbl`')
            ),
            array(
                'DROP VIEW v',
                array()
            ),
            array(
                'DROP TABLE tbl1, tbl2',
                array(
                    '`tbl1`',
                    '`tbl2`',
                ),
            ),
            array(
                'RENAME TABLE a TO b, c TO d',
                array(
                    '`a`',
                    '`c`'
                )
            )
        );
    }

    public function testGetClause()
    {
        /* Assertion 1 */
        $parser = new Parser(
            'SELECT c.city_id, c.country_id ' .
            'FROM `city` ' .
            'WHERE city_id < 1 /* test */' .
            'ORDER BY city_id ASC ' .
            'LIMIT 0, 1 ' .
            'INTO OUTFILE "/dev/null"'
        );
        $this->assertEquals(
            '0, 1 INTO OUTFILE "/dev/null"',
            Query::getClause(
                $parser->statements[0],
                $parser->list,
                'LIMIT',
                0
            )
        );
        // Assert it returns all clauses between FROM and LIMIT
        $this->assertEquals(
            'WHERE city_id < 1 ORDER BY city_id ASC',
            Query::getClause(
                $parser->statements[0],
                $parser->list,
                'FROM',
                'LIMIT'
            )
        );
        // Assert it returns all clauses between SELECT and LIMIT
        $this->assertEquals(
            'FROM `city` WHERE city_id < 1 ORDER BY city_id ASC',
            Query::getClause(
                $parser->statements[0],
                $parser->list,
                'LIMIT',
                'SELECT'
            )
        );

        /* Assertion 2 */
        $parser = new Parser(
            'DELETE FROM `renewal` ' .
            'WHERE number = "1DB" AND actionDate <= CURRENT_DATE() ' .
            'ORDER BY id ASC ' .
            'LIMIT 1'
        );
        $this->assertEquals(
            'number = "1DB" AND actionDate <= CURRENT_DATE()',
            Query::getClause(
                $parser->statements[0],
                $parser->list,
                'WHERE'
            )
        );
        $this->assertEquals(
            '1',
            Query::getClause(
                $parser->statements[0],
                $parser->list,
                'LIMIT'
            )
        );
        $this->assertEquals(
            'id ASC',
            Query::getClause(
                $parser->statements[0],
                $parser->list,
                'ORDER BY'
            )
        );

        /* Assertion 3 */
        $parser = new Parser(
            'UPDATE `renewal` SET `some_column` = 1 ' .
            'WHERE number = "1DB" AND actionDate <= CURRENT_DATE() ' .
            'ORDER BY id ASC ' .
            'LIMIT 1'
        );
        $this->assertEquals(
            'number = "1DB" AND actionDate <= CURRENT_DATE()',
            Query::getClause(
                $parser->statements[0],
                $parser->list,
                'WHERE'
            )
        );
        $this->assertEquals(
            '1',
            Query::getClause(
                $parser->statements[0],
                $parser->list,
                'LIMIT'
            )
        );
        $this->assertEquals(
            'id ASC',
            Query::getClause(
                $parser->statements[0],
                $parser->list,
                'ORDER BY'
            )
        );
    }

    public function testReplaceClause()
    {
        $parser = new Parser('SELECT *, (SELECT 1) FROM film LIMIT 0, 10;');
        $this->assertEquals(
            'SELECT *, (SELECT 1) FROM film WHERE film_id > 0 LIMIT 0, 10',
            Query::replaceClause(
                $parser->statements[0],
                $parser->list,
                'WHERE film_id > 0'
            )
        );

        $parser = new Parser(
            'select supplier.city, supplier.id from supplier '
            . 'union select customer.city, customer.id from customer'
        );
        $this->assertEquals(
            'select supplier.city, supplier.id from supplier '
            . 'union select customer.city, customer.id from customer'
            . ' ORDER BY city ',
            Query::replaceClause(
                $parser->statements[0],
                $parser->list,
                'ORDER BY city'
            )
        );
    }

    public function testReplaceClauseOnlyKeyword()
    {
        $parser = new Parser('SELECT *, (SELECT 1) FROM film LIMIT 0, 10');
        $this->assertEquals(
            ' SELECT SQL_CALC_FOUND_ROWS *, (SELECT 1) FROM film LIMIT 0, 10',
            Query::replaceClause(
                $parser->statements[0],
                $parser->list,
                'SELECT SQL_CALC_FOUND_ROWS',
                null,
                true
            )
        );
    }

    public function testReplaceNonExistingPart()
    {
        $parser = new Parser('ALTER TABLE `sale_mast` OPTIMIZE PARTITION p3');
        $this->assertEquals(
            '  ALTER TABLE `sale_mast` OPTIMIZE PARTITION p3',
            Query::replaceClause(
                $parser->statements[0],
                $parser->list,
                'ORDER BY',
                ''
            )
        );
    }

    public function testReplaceClauses()
    {
        $this->assertEquals('', Query::replaceClauses(null, null, array()));

        $parser = new Parser('SELECT *, (SELECT 1) FROM film LIMIT 0, 10;');
        $this->assertEquals(
            'SELECT *, (SELECT 1) FROM film WHERE film_id > 0 LIMIT 0, 10',
            Query::replaceClauses(
                $parser->statements[0],
                $parser->list,
                array(
                    array(
                        'WHERE',
                        'WHERE film_id > 0',
                    )
                )
            )
        );

        $parser = new Parser(
            'SELECT c.city_id, c.country_id ' .
            'INTO OUTFILE "/dev/null" ' .
            'FROM `city` ' .
            'WHERE city_id < 1 ' .
            'ORDER BY city_id ASC ' .
            'LIMIT 0, 1 '
        );
        $this->assertEquals(
            'SELECT c.city_id, c.country_id ' .
            'INTO OUTFILE "/dev/null" ' .
            'FROM city AS c   ' .
            'ORDER BY city_id ASC ' .
            'LIMIT 0, 10 ',
            Query::replaceClauses(
                $parser->statements[0],
                $parser->list,
                array(
                    array(
                        'FROM',
                        'FROM city AS c',
                    ),
                    array(
                        'WHERE',
                        '',
                    ),
                    array(
                        'LIMIT',
                        'LIMIT 0, 10',
                    )
                )
            )
        );
    }

    public function testGetFirstStatement()
    {
        $query = 'USE saki';
        $delimiter = null;
        list($statement, $query, $delimiter) =
            Query::getFirstStatement($query, $delimiter);
        $this->assertNull($statement);
        $this->assertEquals('USE saki', $query);

        $query = 'USE sakila; ' .
            '/*test comment*/' .
            'SELECT * FROM actor; ' .
            'DELIMITER $$ ' .
            'UPDATE actor SET last_name = "abc"$$' .
            '/*!SELECT * FROM actor WHERE last_name = "abc"*/$$';
        $delimiter = null;

        list($statement, $query, $delimiter) =
            Query::getFirstStatement($query, $delimiter);
        $this->assertEquals('USE sakila;', $statement);

        list($statement, $query, $delimiter) =
            Query::getFirstStatement($query, $delimiter);
        $this->assertEquals('SELECT * FROM actor;', $statement);

        list($statement, $query, $delimiter) =
            Query::getFirstStatement($query, $delimiter);
        $this->assertEquals('DELIMITER $$', $statement);
        $this->assertEquals('$$', $delimiter);

        list($statement, $query, $delimiter) =
            Query::getFirstStatement($query, $delimiter);
        $this->assertEquals('UPDATE actor SET last_name = "abc"$$', $statement);

        list($statement, $query, $delimiter) =
            Query::getFirstStatement($query, $delimiter);
        $this->assertEquals('SELECT * FROM actor WHERE last_name = "abc"$$', $statement);
    }
}
