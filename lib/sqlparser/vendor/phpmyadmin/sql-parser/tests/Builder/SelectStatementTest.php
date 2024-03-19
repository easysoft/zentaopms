<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class SelectStatementTest extends TestCase
{
    public function testBuilder(): void
    {
        $query = 'SELECT * FROM t1 LEFT JOIN (t2, t3, t4) '
            . 'ON (t2.a=t1.a AND t3.b=t1.b AND t4.c=t1.c)';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SELECT * FROM t1 LEFT JOIN (t2, t3, t4) '
            . 'ON (t2.a=t1.a AND t3.b=t1.b AND t4.c=t1.c)',
            $stmt->build()
        );

        $parser = new Parser('SELECT NULL IS NULL');
        $stmt = $parser->statements[0];

        $this->assertEquals('SELECT NULL IS NULL', $stmt->build());

        $parser = new Parser('SELECT NOT 1');
        $stmt = $parser->statements[0];

        $this->assertEquals('SELECT NOT 1', $stmt->build());

        $parser = new Parser('SELECT 1 BETWEEN 0 AND 2');
        $stmt = $parser->statements[0];

        $this->assertEquals('SELECT 1 BETWEEN 0 AND 2', $stmt->build());

        $parser = new Parser("SELECT 'a' NOT REGEXP '^[a-d]'");
        $stmt = $parser->statements[0];

        $this->assertEquals("SELECT 'a' NOT REGEXP '^[a-d]'", $stmt->build());

        $parser = new Parser("SELECT 'a' RLIKE 'a'");
        $stmt = $parser->statements[0];

        $this->assertEquals("SELECT 'a' RLIKE 'a'", $stmt->build());
    }

    public function testBuilderUnion(): void
    {
        $parser = new Parser('SELECT 1 UNION SELECT 2');
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SELECT 1 UNION SELECT 2',
            $stmt->build()
        );
    }

    public function testBuilderWithIsNull(): void
    {
        $parser = new Parser('SELECT `test3`.`t1` is not null AS `is_not_null` FROM `test3` ;');
        $stmt = $parser->statements[0];

        $this->assertEquals('SELECT `test3`.`t1` is not null AS `is_not_null` FROM `test3`', $stmt->build());

        $parser = new Parser('SELECT test3.t1 is null AS `col1` FROM test3');
        $stmt = $parser->statements[0];

        $this->assertEquals('SELECT test3.t1 is null AS `col1` FROM test3', $stmt->build());
    }

    public function testBuilderOrderByNull(): void
    {
        $query = 'SELECT * FROM some_table ORDER BY some_col IS NULL DESC;';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals('SELECT * FROM some_table ORDER BY some_col IS NULL DESC', $stmt->build());

        $query = 'SELECT * FROM some_table ORDER BY some_col IS NOT NULL;';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals('SELECT * FROM some_table ORDER BY some_col IS NOT NULL ASC', $stmt->build());
    }

    public function testBuilderAlias(): void
    {
        $parser = new Parser(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` sgu '
            . 'RIGHT JOIN `student_course_booking` scb ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' GROUP BY sgu.id '
            . 'ORDER BY scb.id DESC LIMIT 0,300'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` AS `sgu` '
            . 'RIGHT JOIN `student_course_booking` AS `scb` ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' GROUP BY sgu.id '
            . 'ORDER BY scb.id DESC LIMIT 0, 300',
            $stmt->build()
        );
    }

    public function testBuilderAliasOrder(): void
    {
        $parser = new Parser(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` sgu '
            . 'RIGHT JOIN `student_course_booking` scb ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' GROUP BY sgu.id '
            . 'ORDER BY scb.id LIMIT 0,300'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` AS `sgu` '
            . 'RIGHT JOIN `student_course_booking` AS `scb` ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' GROUP BY sgu.id '
            . 'ORDER BY scb.id ASC LIMIT 0, 300',
            $stmt->build()
        );
    }

    public function testBuilderAliasOrderMultiple(): void
    {
        $parser = new Parser(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` sgu '
            . 'RIGHT JOIN `student_course_booking` scb ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' GROUP BY sgu.id '
            . 'ORDER BY scb.id DESC, scb.order LIMIT 0,300'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` AS `sgu` '
            . 'RIGHT JOIN `student_course_booking` AS `scb` ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' GROUP BY sgu.id '
            . 'ORDER BY scb.id DESC, scb.order ASC LIMIT 0, 300',
            $stmt->build()
        );
    }

    public function testBuilderAliasOrderMultipleFunctions(): void
    {
        $parser = new Parser(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` sgu '
            . 'RIGHT JOIN `student_course_booking` scb ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' GROUP BY sgu.id '
            . 'ORDER BY scb.id DESC, YEAR(scb.dob) LIMIT 0,300'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` AS `sgu` '
            . 'RIGHT JOIN `student_course_booking` AS `scb` ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' GROUP BY sgu.id '
            . 'ORDER BY scb.id DESC, YEAR(scb.dob) ASC LIMIT 0, 300',
            $stmt->build()
        );
    }

    public function testBuilderAliasGroupByMultipleFunctions(): void
    {
        $parser = new Parser(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` sgu '
            . 'RIGHT JOIN `student_course_booking` scb ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' '
            . 'GROUP BY scb.id, YEAR(scb.dob) LIMIT 0,300'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` AS `sgu` '
            . 'RIGHT JOIN `student_course_booking` AS `scb` ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' '
            . 'GROUP BY scb.id, YEAR(scb.dob) LIMIT 0, 300',
            $stmt->build()
        );
    }

    public function testBuilderAliasGroupByMultipleFunctionsOrderRemoved(): void
    {
        $parser = new Parser(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` sgu '
            . 'RIGHT JOIN `student_course_booking` scb ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' '
            . 'GROUP BY scb.id ASC, YEAR(scb.dob) DESC LIMIT 0,300'
        );
        $stmt = $parser->statements[0];

        // The order is not kept, is this an expected behavior ?
        // Ref: 4af06d24b041e499fb0e75ab3a98caf9a91700ef
        // Issue: #154
        $this->assertEquals(
            'SELECT sgu.id, sgu.email_address FROM `sf_guard_user` AS `sgu` '
            . 'RIGHT JOIN `student_course_booking` AS `scb` ON sgu.id = scb.user_id '
            . 'WHERE `has_found_course` = \'1\' '
            . 'GROUP BY scb.id, YEAR(scb.dob) LIMIT 0, 300',
            $stmt->build()
        );
    }

    public function testBuilderAliasOrderCase(): void
    {
        $parser = new Parser(
            'SELECT * FROM `world_borders` ORDER BY CASE '
            . 'WHEN REGION = 2 THEN 99 '
            . 'WHEN REGION > 3 THEN REGION+1 '
            . 'ELSE 100 END LIMIT 0,300'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SELECT * FROM `world_borders` ORDER BY CASE '
            . 'WHEN REGION = 2 THEN 99 '
            . 'WHEN REGION > 3 THEN REGION+1 '
            . 'ELSE 100 END ASC LIMIT 0, 300',
            $stmt->build()
        );
    }

    public function testBuilderAliasGroupByCase(): void
    {
        $parser = new Parser(
            'SELECT * FROM `world_borders` GROUP BY CASE '
            . 'WHEN REGION = 2 THEN 99 '
            . 'WHEN REGION > 3 THEN REGION+1 '
            . 'ELSE 100 END LIMIT 0,300'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SELECT * FROM `world_borders` GROUP BY CASE '
            . 'WHEN REGION = 2 THEN 99 '
            . 'WHEN REGION > 3 THEN REGION+1 '
            . 'ELSE 100 END LIMIT 0, 300',
            $stmt->build()
        );
    }

    public function testBuilderEndOptions(): void
    {
        /* Assertion 1 */
        $query = 'SELECT pid, name2 FROM tablename WHERE pid = 20 FOR UPDATE';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            $query,
            $stmt->build()
        );

        /* Assertion 2 */
        $query = 'SELECT pid, name2 FROM tablename WHERE pid = 20 LOCK IN SHARE MODE';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            $query,
            $stmt->build()
        );
    }

    public function testBuilderIntoOptions(): void
    {
        /* Assertion 1 */
        $query = 'SELECT a, b, a+b INTO OUTFILE "/tmp/result.txt"'
            . ' COLUMNS TERMINATED BY \',\' OPTIONALLY ENCLOSED BY \'"\''
            . ' LINES TERMINATED BY \'\n\''
            . ' FROM test_table';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            $query,
            $stmt->build()
        );
    }

    public function testBuilderGroupBy(): void
    {
        $query = 'SELECT COUNT(CustomerID), Country FROM Customers GROUP BY Country';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            $query,
            $stmt->build()
        );
    }

    public function testBuilderGroupByWithRollup(): void
    {
        $query = 'SELECT year FROM movies GROUP BY year WITH ROLLUP';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            $query,
            $stmt->build()
        );
    }

    public function testBuilderGroupByMultipleColumnsWithRollup(): void
    {
        $query = 'SELECT title, year FROM movies GROUP BY title, year WITH ROLLUP';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            $query,
            $stmt->build()
        );
    }

    public function testBuilderGroupByWithRollupWithOtherClauses(): void
    {
        $query = 'SELECT year FROM movies GROUP BY year WITH ROLLUP ORDER BY year ASC LIMIT 0, 5';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            $query,
            $stmt->build()
        );
    }

    public function testBuilderIndexHint(): void
    {
        $query = 'SELECT * FROM address FORCE INDEX (idx_fk_city_id) IGNORE KEY FOR GROUP BY (a, b,c) WHERE city_id<0';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            $query,
            $stmt->build()
        );
    }

    public function testBuilderSurroundedByParanthesisWithLimit(): void
    {
        $query = '(SELECT first_name FROM `actor` LIMIT 1, 2)';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SELECT first_name FROM `actor` LIMIT 1, 2',
            $stmt->build()
        );
    }
}
