<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Utils;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Utils\Query;

use function array_merge;

/**
 * @psalm-import-type QueryFlagsType from Query
 */
class QueryTest extends TestCase
{
    /**
     * @param array<string, bool|string> $expected
     * @psalm-param QueryFlagsType $expected
     *
     * @dataProvider getFlagsProvider
     */
    public function testGetFlags(string $query, array $expected): void
    {
        $parser = new Parser($query);
        $this->assertEquals($expected, Query::getFlags($parser->statements[0]));
    }

    /**
     * @return array<int, array<int, string|array<string, bool|string>>>
     * @psalm-return list<array{non-empty-string, QueryFlagsType}>
     */
    public function getFlagsProvider(): array
    {
        return [
            [
                'ALTER TABLE DROP col',
                [
                    'reload' => true,
                    'querytype' => 'ALTER',
                ],
            ],
            [
                'CALL test()',
                [
                    'is_procedure' => true,
                    'querytype' => 'CALL',
                ],
            ],
            [
                'CREATE TABLE tbl (id INT)',
                [
                    'reload' => true,
                    'querytype' => 'CREATE',
                ],
            ],
            [
                'CHECK TABLE tbl',
                [
                    'is_maint' => true,
                    'querytype' => 'CHECK',
                ],
            ],
            [
                'DELETE FROM tbl',
                [
                    'is_affected' => true,
                    'is_delete' => true,
                    'querytype' => 'DELETE',
                ],
            ],
            [
                'DROP VIEW v',
                [
                    'reload' => true,
                    'querytype' => 'DROP',
                ],
            ],
            [
                'DROP DATABASE db',
                [
                    'drop_database' => true,
                    'reload' => true,
                    'querytype' => 'DROP',
                ],
            ],
            [
                'EXPLAIN tbl',
                [
                    'is_explain' => true,
                    'querytype' => 'EXPLAIN',
                ],
            ],
            [
                'LOAD DATA INFILE \'/tmp/test.txt\' INTO TABLE test',
                [
                    'is_affected' => true,
                    'is_insert' => true,
                    'querytype' => 'LOAD',
                ],
            ],
            [
                'INSERT INTO tbl VALUES (1)',
                [
                    'is_affected' => true,
                    'is_insert' => true,
                    'querytype' => 'INSERT',
                ],
            ],
            [
                'REPLACE INTO tbl VALUES (2)',
                [
                    'is_affected' => true,
                    'is_replace' => true,
                    'is_insert' => true,
                    'querytype' => 'REPLACE',
                ],
            ],
            [
                'SELECT 1',
                [
                    'is_select' => true,
                    'querytype' => 'SELECT',
                ],
            ],
            [
                'SELECT * FROM tbl',
                [
                    'is_select' => true,
                    'select_from' => true,
                    'querytype' => 'SELECT',
                ],
            ],
            [
                'SELECT DISTINCT * FROM tbl LIMIT 0, 10 ORDER BY id',
                [
                    'distinct' => true,
                    'is_select' => true,
                    'select_from' => true,
                    'limit' => true,
                    'order' => true,
                    'querytype' => 'SELECT',
                ],
            ],
            [
                'SELECT * FROM actor GROUP BY actor_id',
                [
                    'is_group' => true,
                    'is_select' => true,
                    'select_from' => true,
                    'group' => true,
                    'querytype' => 'SELECT',
                ],
            ],
            [
                'SELECT col1, col2 FROM table1 PROCEDURE ANALYSE(10, 2000);',
                [
                    'is_analyse' => true,
                    'is_select' => true,
                    'select_from' => true,
                    'querytype' => 'SELECT',
                ],
            ],
            [
                'SELECT * FROM tbl INTO OUTFILE "/tmp/export.txt"',
                [
                    'is_export' => true,
                    'is_select' => true,
                    'select_from' => true,
                    'querytype' => 'SELECT',
                ],
            ],
            [
                'SELECT COUNT(id), SUM(id) FROM tbl',
                [
                    'is_count' => true,
                    'is_func' => true,
                    'is_select' => true,
                    'select_from' => true,
                    'querytype' => 'SELECT',
                ],
            ],
            [
                'SELECT (SELECT "foo")',
                [
                    'is_select' => true,
                    'is_subquery' => true,
                    'querytype' => 'SELECT',
                ],
            ],
            [
                'SELECT * FROM customer HAVING store_id = 2;',
                [
                    'is_select' => true,
                    'select_from' => true,
                    'is_group' => true,
                    'having' => true,
                    'querytype' => 'SELECT',
                ],
            ],
            [
                'SELECT * FROM table1 INNER JOIN table2 ON table1.id=table2.id;',
                [
                    'is_select' => true,
                    'select_from' => true,
                    'join' => true,
                    'querytype' => 'SELECT',
                ],
            ],
            [
                'SHOW CREATE TABLE tbl',
                [
                    'is_show' => true,
                    'querytype' => 'SHOW',
                ],
            ],
            [
                'UPDATE tbl SET id = 1',
                [
                    'is_affected' => true,
                    'querytype' => 'UPDATE',
                ],
            ],
            [
                'ANALYZE TABLE tbl',
                [
                    'is_maint' => true,
                    'querytype' => 'ANALYZE',
                ],
            ],
            [
                'CHECKSUM TABLE tbl',
                [
                    'is_maint' => true,
                    'querytype' => 'CHECKSUM',
                ],
            ],
            [
                'OPTIMIZE TABLE tbl',
                [
                    'is_maint' => true,
                    'querytype' => 'OPTIMIZE',
                ],
            ],
            [
                'REPAIR TABLE tbl',
                [
                    'is_maint' => true,
                    'querytype' => 'REPAIR',
                ],
            ],
            [
                '(SELECT a FROM t1 WHERE a=10 AND B=1 ORDER BY a LIMIT 10) ' .
                'UNION ' .
                '(SELECT a FROM t2 WHERE a=11 AND B=2 ORDER BY a LIMIT 10);',
                [
                    'is_select' => true,
                    'select_from' => true,
                    'limit' => true,
                    'order' => true,
                    'union' => true,
                    'querytype' => 'SELECT',
                ],
            ],
            [
                'SELECT * FROM orders AS ord WHERE 1',
                [
                    'querytype' => 'SELECT',
                    'is_select' => true,
                    'select_from' => true,
                ],
            ],
            [
                'SET NAMES \'latin\'',
                ['querytype' => 'SET'],
            ],
        ];
    }

    public function testGetAll(): void
    {
        $this->assertEquals(
            [
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
            ],
            Query::getAll('')
        );

        $query = 'SELECT *, actor.actor_id, sakila2.film.*
            FROM sakila2.city, sakila2.film, actor';
        $parser = new Parser($query);
        $this->assertEquals(
            array_merge(
                Query::getFlags($parser->statements[0], true),
                [
                    'parser' => $parser,
                    'statement' => $parser->statements[0],
                    'select_expr' => ['*'],
                    'select_tables' => [
                        [
                            'actor',
                            null,
                        ],
                        [
                            'film',
                            'sakila2',
                        ],
                    ],
                ]
            ),
            Query::getAll($query)
        );

        $query = 'SELECT * FROM sakila.actor, film';
        $parser = new Parser($query);
        $this->assertEquals(
            array_merge(
                Query::getFlags($parser->statements[0], true),
                [
                    'parser' => $parser,
                    'statement' => $parser->statements[0],
                    'select_expr' => ['*'],
                    'select_tables' => [
                        [
                            'actor',
                            'sakila',
                        ],
                        [
                            'film',
                            null,
                        ],
                    ],
                ]
            ),
            Query::getAll($query)
        );

        $query = 'SELECT a.actor_id FROM sakila.actor AS a, film';
        $parser = new Parser($query);
        $this->assertEquals(
            array_merge(
                Query::getFlags($parser->statements[0], true),
                [
                    'parser' => $parser,
                    'statement' => $parser->statements[0],
                    'select_expr' => [],
                    'select_tables' => [
                        [
                            'actor',
                            'sakila',
                        ],
                    ],
                ]
            ),
            Query::getAll($query)
        );

        $query = 'SELECT CASE WHEN 2 IS NULL THEN "this is true" ELSE "this is false" END';
        $parser = new Parser($query);
        $this->assertEquals(
            array_merge(
                Query::getFlags($parser->statements[0], true),
                [
                    'parser' => $parser,
                    'statement' => $parser->statements[0],
                    'select_expr' => ['CASE WHEN 2 IS NULL THEN "this is true" ELSE "this is false" END'],
                    'select_tables' => [],
                ]
            ),
            Query::getAll($query)
        );
    }

    /**
     * @param string[] $expected
     *
     * @dataProvider getTablesProvider
     */
    public function testGetTables(string $query, array $expected): void
    {
        $parser = new Parser($query);
        $this->assertEquals(
            $expected,
            Query::getTables($parser->statements[0])
        );
    }

    /**
     * @return array<int, array<int, string|string[]>>
     * @psalm-return list<array{string, string[]}>
     */
    public function getTablesProvider(): array
    {
        return [
            [
                'INSERT INTO tbl(`id`, `name`) VALUES (1, "Name")',
                ['`tbl`'],
            ],
            [
                'INSERT INTO 0tbl(`id`, `name`) VALUES (1, "Name")',
                ['`0tbl`'],
            ],
            [
                'UPDATE tbl SET id = 0',
                ['`tbl`'],
            ],
            [
                'UPDATE 0tbl SET id = 0',
                ['`0tbl`'],
            ],
            [
                'DELETE FROM tbl WHERE id < 10',
                ['`tbl`'],
            ],
            [
                'DELETE FROM 0tbl WHERE id < 10',
                ['`0tbl`'],
            ],
            [
                'TRUNCATE tbl',
                ['`tbl`'],
            ],
            [
                'DROP VIEW v',
                [],
            ],
            [
                'DROP TABLE tbl1, tbl2',
                [
                    '`tbl1`',
                    '`tbl2`',
                ],
            ],
            [
                'RENAME TABLE a TO b, c TO d',
                [
                    '`a`',
                    '`c`',
                ],
            ],
        ];
    }

    public function testGetClause(): void
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

    public function testReplaceClause(): void
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

    public function testReplaceClauseOnlyKeyword(): void
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

    public function testReplaceNonExistingPart(): void
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

    public function testReplaceClauses(): void
    {
        $parser = new Parser('SELECT *, (SELECT 1) FROM film LIMIT 0, 10;');
        $this->assertSame('', Query::replaceClauses($parser->statements[0], $parser->list, []));
        $this->assertEquals(
            'SELECT *, (SELECT 1) FROM film WHERE film_id > 0 LIMIT 0, 10',
            Query::replaceClauses(
                $parser->statements[0],
                $parser->list,
                [
                    [
                        'WHERE',
                        'WHERE film_id > 0',
                    ],
                ]
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
                [
                    [
                        'FROM',
                        'FROM city AS c',
                    ],
                    [
                        'WHERE',
                        '',
                    ],
                    [
                        'LIMIT',
                        'LIMIT 0, 10',
                    ],
                ]
            )
        );
    }

    public function testGetFirstStatement(): void
    {
        $query = 'USE saki';
        $delimiter = null;
        [$statement, $query, $delimiter] = Query::getFirstStatement($query, $delimiter);
        $this->assertNull($statement);
        $this->assertEquals('USE saki', $query);

        $query = 'USE sakila; ' .
            '/*test comment*/' .
            'SELECT * FROM actor; ' .
            'DELIMITER $$ ' .
            'UPDATE actor SET last_name = "abc"$$' .
            '/*!SELECT * FROM actor WHERE last_name = "abc"*/$$';
        $delimiter = null;

        [$statement, $query, $delimiter] = Query::getFirstStatement($query, $delimiter);
        $this->assertEquals('USE sakila;', $statement);

        [$statement, $query, $delimiter] = Query::getFirstStatement($query, $delimiter);
        $this->assertEquals('SELECT * FROM actor;', $statement);

        [$statement, $query, $delimiter] = Query::getFirstStatement($query, $delimiter);
        $this->assertEquals('DELIMITER $$', $statement);
        $this->assertEquals('$$', $delimiter);

        [$statement, $query, $delimiter] = Query::getFirstStatement($query, $delimiter);
        $this->assertEquals('UPDATE actor SET last_name = "abc"$$', $statement);

        [$statement, $query, $delimiter] = Query::getFirstStatement($query, $delimiter);
        $this->assertEquals('SELECT * FROM actor WHERE last_name = "abc"$$', $statement);
    }
}
