<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Components\CreateDefinition;
use PhpMyAdmin\SqlParser\Components\DataType;
use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Components\Key;
use PhpMyAdmin\SqlParser\Components\OptionsArray;
use PhpMyAdmin\SqlParser\Components\ParameterDefinition;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Statements\CreateStatement;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\TokensList;

class CreateStatementTest extends TestCase
{
    public function testBuilder(): void
    {
        $parser = new Parser('CREATE USER "jeffrey"@"localhost" IDENTIFIED BY "mypass"');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'CREATE USER "jeffrey"@"localhost" IDENTIFIED BY "mypass"',
            $stmt->build()
        );
    }

    public function testBuilderDatabase(): void
    {
        // CREATE DATABASE ...
        $parser = new Parser(
            'CREATE DATABASE `mydb` ' .
            'DEFAULT CHARACTER SET = utf8 DEFAULT COLLATE = utf8_general_ci'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'CREATE DATABASE `mydb` ' .
            'DEFAULT CHARACTER SET=utf8 DEFAULT COLLATE=utf8_general_ci',
            $stmt->build()
        );

        // CREATE SCHEMA ...
        $parser = new Parser('CREATE SCHEMA `mydb` DEFAULT CHARACTER SET = utf8 DEFAULT COLLATE = utf8_general_ci');
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'CREATE SCHEMA `mydb` ' .
            'DEFAULT CHARACTER SET=utf8 DEFAULT COLLATE=utf8_general_ci',
            $stmt->build()
        );
    }

    public function testBuilderDefaultInt(): void
    {
        $parser = new Parser(
            'CREATE TABLE IF NOT EXISTS t1 (' .
            " c1 int(11) NOT NULL DEFAULT '0' COMMENT 'xxx'" .
            ') ENGINE=MyISAM'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            "CREATE TABLE IF NOT EXISTS t1 (\n" .
            "  `c1` int(11) NOT NULL DEFAULT '0' COMMENT 'xxx'\n" .
            ') ENGINE=MyISAM',
            $stmt->build()
        );
    }

    public function testBuilderWithComments(): void
    {
        $parser = new Parser('CREATE TABLE tab1 (`col1` TIMESTAMP /*!40100 DEFAULT NULL */)');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            // TODO: fix with https://github.com/phpmyadmin/sql-parser/issues/256
            "CREATE TABLE tab1 (\n  `col1` timestamp DEFAULT NULL\n) ",
            $stmt->build()
        );
    }

    public function testBuilderCompressed(): void
    {
        $parser = new Parser('CREATE TABLE users ( user_id int ) PAGE_COMPRESSED=1 PAGE_COMPRESSION_LEVEL=9;');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            "CREATE TABLE users (\n  `user_id` int\n) PAGE_COMPRESSED=1 PAGE_COMPRESSION_LEVEL=9",
            $stmt->build()
        );
    }

    public function testBuilderCollate(): void
    {
        $parser = new Parser(
            'CREATE TABLE IF NOT EXISTS t1 (' .
            " c1 varchar(11) NOT NULL DEFAULT '0' COLLATE 'utf8_czech_ci' COMMENT 'xxx'" .
            ') ENGINE=MyISAM'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            "CREATE TABLE IF NOT EXISTS t1 (\n" .
            "  `c1` varchar(11) NOT NULL DEFAULT '0' COLLATE 'utf8_czech_ci' COMMENT 'xxx'\n" .
            ') ENGINE=MyISAM',
            $stmt->build()
        );
    }

    public function testBuilderDefaultComment(): void
    {
        $parser = new Parser(
            'CREATE TABLE `wp_audio` (' .
            " `somedata` int(11) DEFAULT NULL COMMENT 'ma data', " .
            " `someinfo` int(11) DEFAULT NULL COMMENT 'ma info' " .
            ' )'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            "CREATE TABLE `wp_audio` (\n" .
            "  `somedata` int(11) DEFAULT NULL COMMENT 'ma data',\n" .
            "  `someinfo` int(11) DEFAULT NULL COMMENT 'ma info'\n" .
            ') ',
            $stmt->build()
        );
    }

    public function testBuilderTable(): void
    {
        /* Assertion 1 */
        $stmt = new CreateStatement();

        $stmt->name = new Expression('', 'test', '');
        $stmt->options = new OptionsArray(['TABLE']);
        $stmt->fields = [
            new CreateDefinition(
                'id',
                new OptionsArray(['NOT NULL', 'AUTO_INCREMENT']),
                new DataType('INT', [11], new OptionsArray(['UNSIGNED']))
            ),
            new CreateDefinition(
                '',
                null,
                new Key('', [['name' => 'id']], 'PRIMARY KEY')
            ),
        ];

        $this->assertEquals(
            "CREATE TABLE `test` (\n" .
            "  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,\n" .
            "  PRIMARY KEY (`id`)\n" .
            ') ',
            $stmt->build()
        );

        /* Assertion 2 */
        $query =
            "CREATE TABLE `jos_core_acl_aro` (\n" .
            "  `id` int(11) NOT NULL,\n" .
            "  `section_value` varchar(240) NOT NULL DEFAULT '0',\n" .
            "  `value` varchar(240) NOT NULL DEFAULT '',\n" .
            "  `order_value` int(11) NOT NULL DEFAULT '0',\n" .
            "  `name` varchar(255) NOT NULL DEFAULT '',\n" .
            "  `hidden` int(11) NOT NULL DEFAULT '0',\n" .
            "  PRIMARY KEY (`id`),\n" .
            "  UNIQUE KEY `jos_section_value_value_aro` (`section_value`(100),`value`(15)) USING BTREE,\n" .
            "  KEY `jos_gacl_hidden_aro` (`hidden`)\n" .
            ') ENGINE=InnoDB DEFAULT CHARSET=latin1';
        $parser = new Parser($query);
        $this->assertEquals($query, $parser->statements[0]->build());

        /* Assertion 3 */
        $query = 'CREATE TABLE `table_copy` LIKE `old_table`';
        $parser = new Parser($query);
        $this->assertEquals($query, $parser->statements[0]->build());

        /* Assertion 4 */
        $query =
            "CREATE TABLE `aa` (\n" .
            "  `id` int(11) NOT NULL,\n" .
            "  `rTime` timestamp(3) NOT NULL DEFAULT '0000-00-00 00:00:00.000' ON UPDATE CURRENT_TIMESTAMP(3),\n" .
            "  PRIMARY KEY (`id`)\n" .
            ') ENGINE=InnoDB DEFAULT CHARSET=latin1';
        $parser = new Parser($query);
        $this->assertEquals($query, $parser->statements[0]->build());

        /* Assertion 5 */
        $parser = new Parser(
            'CREATE table table_name WITH' .
            ' cte (col1) AS ( SELECT 1 UNION ALL SELECT 2 )' .
            ' SELECT col1 FROM cte'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'CREATE TABLE table_name WITH' .
            ' cte(col1) AS (SELECT 1 UNION ALL SELECT 2)' .
            ' SELECT col1 FROM cte',
            $stmt->build()
        );
    }

    public function testBuilderPartitions(): void
    {
        /* Assertion 1 */
        $query = 'CREATE TABLE ts (' . "\n"
            . '  `id` int,' . "\n"
            . '  `purchased` date' . "\n"
            . ') ' . "\n"
            . 'PARTITION BY RANGE(YEAR(purchased))' . "\n"
            . 'PARTITIONS 3' . "\n"
            . 'SUBPARTITION BY HASH(TO_DAYS(purchased))' . "\n"
            . 'SUBPARTITIONS 2' . "\n"
            . '(' . "\n"
            . 'PARTITION p0 VALUES LESS THAN (1990)  (' . "\n"
            . 'SUBPARTITION s0,' . "\n"
            . 'SUBPARTITION s1' . "\n"
            . '),' . "\n"
            . 'PARTITION p1 VALUES LESS THAN (2000)  (' . "\n"
            . 'SUBPARTITION s2,' . "\n"
            . 'SUBPARTITION s3' . "\n"
            . '),' . "\n"
            . 'PARTITION p2 VALUES LESS THAN MAXVALUE  (' . "\n"
            . 'SUBPARTITION s4,' . "\n"
            . 'SUBPARTITION s5' . "\n"
            . ')' . "\n"
            . ')';
        $parser = new Parser($query);
        $this->assertEquals($query, $parser->statements[0]->build());

        /* Assertion 2 */
        $query = 'CREATE TABLE `pma_test` (' . "\n"
            . '  `test_id` int(32) NOT NULL,' . "\n"
            . '  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP' . "\n"
            . ') ENGINE=InnoDB DEFAULT CHARSET=utf8' . "\n"
            . 'PARTITION BY RANGE (test_id)' . "\n"
            . '(' . "\n"
            . 'PARTITION p0 VALUES LESS THAN (250000) ENGINE=InnoDB,' . "\n"
            . 'PARTITION p1 VALUES LESS THAN (500000) ENGINE=InnoDB,' . "\n"
            . 'PARTITION p2 VALUES LESS THAN (750000) ENGINE=InnoDB,' . "\n"
            . 'PARTITION p3 VALUES LESS THAN (1000000) ENGINE=InnoDB,' . "\n"
            . 'PARTITION p4 VALUES LESS THAN (1250000) ENGINE=InnoDB,' . "\n"
            . 'PARTITION p5 VALUES LESS THAN (1500000) ENGINE=InnoDB,' . "\n"
            . 'PARTITION p6 VALUES LESS THAN MAXVALUE ENGINE=InnoDB' . "\n"
            . ')';
        $parser = new Parser($query);
        $this->assertEquals($query, $parser->statements[0]->build());
    }

    /**
     * @return string[][]
     */
    public function partitionQueriesProvider(): array
    {
        return [
            [
                'subparts' => <<<EOT
CREATE TABLE `ts` (
  `id` int(11) DEFAULT NULL,
  `purchased` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
PARTITION BY RANGE (YEAR(purchased))
SUBPARTITION BY HASH (TO_DAYS(purchased))
(
PARTITION p0 VALUES LESS THAN (1990)  (
SUBPARTITION s0 ENGINE=InnoDB,
SUBPARTITION s1 ENGINE=InnoDB
),
PARTITION p1 VALUES LESS THAN (2000)  (
SUBPARTITION s2 ENGINE=InnoDB,
SUBPARTITION s3 ENGINE=InnoDB
),
PARTITION p2 VALUES LESS THAN MAXVALUE  (
SUBPARTITION s4 ENGINE=InnoDB,
SUBPARTITION s5 ENGINE=InnoDB
)
)
EOT
            ,
            ],
            [
                'parts' => <<<EOT
CREATE TABLE ptest (
  `event_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC
PARTITION BY HASH (TO_DAYS(event_date))
(
PARTITION p0 ENGINE=InnoDB,
PARTITION p1 ENGINE=InnoDB,
PARTITION p2 ENGINE=InnoDB,
PARTITION p3 ENGINE=InnoDB,
PARTITION p4 ENGINE=InnoDB
)
EOT
            ,
            ],
        ];
    }

    /**
     * @dataProvider partitionQueriesProvider
     */
    public function testBuilderPartitionsEngine(string $query): void
    {
        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }

    public function testBuilderView(): void
    {
        $parser = new Parser(
            'CREATE OR REPLACE VIEW xviewmytable  AS SELECT mytable.id '
            . 'AS id, mytable.personid AS personid FROM mytable '
            . 'WHERE (mytable.birth > \'1990-01-19\') GROUP BY mytable.personid  ;'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'CREATE OR REPLACE VIEW xviewmytable  AS SELECT mytable.id '
            . 'AS `id`, mytable.personid AS `personid` FROM mytable '
            . 'WHERE (mytable.birth > \'1990-01-19\') GROUP BY mytable.personid ',
            $stmt->build()
        );

        $parser = new Parser(
            'CREATE VIEW myView (vid, vfirstname) AS ' .
            'SELECT id, first_name FROM employee WHERE id = 1'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'CREATE VIEW myView (vid, vfirstname) AS  ' .
            'SELECT id, first_name FROM employee WHERE id = 1 ',
            $stmt->build()
        );

        $parser = new Parser(
            'CREATE OR REPLACE VIEW myView (vid, vfirstname) AS ' .
            'SELECT id, first_name FROM employee WHERE id = 1'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'CREATE OR REPLACE VIEW myView (vid, vfirstname) AS  ' .
            'SELECT id, first_name FROM employee WHERE id = 1 ',
            $stmt->build()
        );

        // Assert the builder can build wrong syntax select expressions
        $parser = new Parser(
            'CREATE OR REPLACE VIEW myView (vid, vfirstname) AS ' .
            'SELECT id, first_name, FROMzz employee WHERE id = 1'
        );
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'CREATE OR REPLACE VIEW myView (vid, vfirstname) AS  ' .
            'SELECT id, first_name, FROMzz employee WHERE id = 1 ',
            $stmt->build()
        );

        $parser = new Parser(
            'CREATE OR REPLACE VIEW myView (vid, vfirstname) AS ' .
            'SELECT id, first_name, FROMzz employee WHERE id = 1 ' .
            'UNION ' .
            'SELECT id, first_name, FROMzz employee WHERE id = 2 '
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'CREATE OR REPLACE VIEW myView (vid, vfirstname) AS  ' .
            'SELECT id, first_name, FROMzz employee WHERE id = 1 ' .
            'UNION ' .
            'SELECT id, first_name, FROMzz employee WHERE id = 2  ',
            $stmt->build()
        );

        $parser = new Parser('CREATE VIEW `view_programlevelpartner`  AS SELECT `p`.`country_id`'
        . 'AS `country_id` FROM `program_level_partner` `p` ORDER BY `p`.`id` asc');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'CREATE VIEW `view_programlevelpartner`  AS SELECT `p`.`country_id`'
            . ' AS `country_id` FROM `program_level_partner` AS `p` ORDER BY `p`.`id` ASC ',
            $stmt->build()
        );

        $parser = new Parser('CREATE VIEW `view_zg_bycountry`  AS '
        . 'SELECT `d`.`zg_id` FROM `view_zg_value` AS `d` GROUP BY `d`.`ind_id`;');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'CREATE VIEW `view_zg_bycountry`  AS '
            . 'SELECT `d`.`zg_id` FROM `view_zg_value` AS `d` GROUP BY `d`.`ind_id` ',
            $stmt->build()
        );

        $parser = new Parser('CREATE view view_name AS WITH  aa(col1)'
        . ' AS ( SELECT 1 UNION ALL SELECT 2 ) SELECT col1 FROM cte AS `d` ');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'CREATE view view_name  AS WITH aa(col1)'
            . ' AS (SELECT 1 UNION ALL SELECT 2) SELECT col1 FROM cte AS `d`  ',
            $stmt->build()
        );
    }

    public function testBuilderViewComplex(): void
    {
        $parser = new Parser(
            'CREATE VIEW withclause AS' . "\n"
            . "\n"
            . 'WITH cte AS (' . "\n"
                . 'SELECT p.name, p.shape' . "\n"
                . 'FROM gis_all as p' . "\n"
            . ')' . "\n"
            . "\n"
            . 'SELECT cte.*' . "\n"
            . 'FROM cte' . "\n"
            . 'CROSS JOIN gis_all;'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'CREATE VIEW withclause  AS '
            . 'WITH cte AS ('
                . 'SELECT p.name, p.shape '
                . 'FROM gis_all AS `p`'
            . ') '
            . 'SELECT cte.* '
            . 'FROM cte '
            . 'CROSS JOIN gis_all ',
            $stmt->build()
        );
        $parser = new Parser(
            'CREATE VIEW withclause2 AS' . "\n"
            . "\n"
            . 'WITH cte AS (' . "\n"
                . "\t" . 'SELECT p.name, p.shape' . "\n"
                . "\t" . 'FROM gis_all as p' . "\n"
            . '), cte2 AS (' . "\n"
                . "\t" . 'SELECT p.name as n2, p.shape as sh2' . "\n"
                . "\t" . 'FROM gis_all as p' . "\n"
            . ')' . "\n"
            . "\n"
            . 'SELECT cte.*,cte2.*' . "\n"
            . 'FROM cte,cte2' . "\n"
            . 'CROSS JOIN gis_all;'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'CREATE VIEW withclause2  AS '
            . 'WITH cte AS ('
                . 'SELECT p.name, p.shape'
                . ' FROM gis_all AS `p`'
            . '), cte2 AS ('
                . 'SELECT p.name AS `n2`, p.shape AS `sh2`'
                . ' FROM gis_all AS `p`'
            . ')'
            . ' SELECT cte.*, cte2.* '
            . 'FROM cte, cte2'
            . ' CROSS JOIN gis_all ',
            $stmt->build()
        );
    }

    public function testBuilderCreateProcedure(): void
    {
        $parser = new Parser(
            'CREATE DEFINER=`root`@`%`'
            . ' PROCEDURE `test2`(IN `_var` INT) DETERMINISTIC'
            . ' MODIFIES SQL DATA SELECT _var'
        );

        /** @var CreateStatement $stmt */
        $stmt = $parser->statements[0];

        $this->assertSame(
            'CREATE DEFINER=`root`@`%`'
            . ' PROCEDURE `test2` (IN `_var` INT)  DETERMINISTIC'
            . ' MODIFIES SQL DATA SELECT _var',
            $stmt->build()
        );

        $parser = new Parser(
            'CREATE DEFINER=`root`@`%`'
            . ' PROCEDURE `test2`(IN `_var` INT) NOT DETERMINISTIC NO SQL'
            . ' SQL SECURITY INVOKER NO SQL SQL SECURITY INVOKER SELECT _var'
        );

        /** @var CreateStatement $stmt */
        $stmt = $parser->statements[0];

        $this->assertSame(
            'CREATE DEFINER=`root`@`%`'
            . ' PROCEDURE `test2` (IN `_var` INT)  NOT DETERMINISTIC NO SQL'
            . ' SQL SECURITY INVOKER NO SQL SQL SECURITY INVOKER SELECT _var',
            $stmt->build()
        );

        $this->assertFalse($stmt->entityOptions->isEmpty());
        $this->assertFalse($stmt->options->isEmpty());
        $this->assertSame(
            'DEFINER=`root`@`%` PROCEDURE',
            $stmt->options->__toString()
        );

        $this->assertSame(
            '`test2`',
            $stmt->name->__toString()
        );

        $this->assertSame(
            '(IN `_var` INT)',
            ParameterDefinition::build($stmt->parameters)
        );

        $this->assertSame(
            'NOT DETERMINISTIC NO SQL SQL SECURITY INVOKER NO SQL SQL SECURITY INVOKER',
            $stmt->entityOptions->__toString()
        );

        $this->assertSame(
            'SELECT _var',
            TokensList::build($stmt->body)
        );
    }

    public function testBuilderCreateFunction(): void
    {
        $parser = new Parser(
            'DELIMITER $$' . "\n"
            . 'CREATE DEFINER=`root`@`localhost`'
            . ' FUNCTION `inventory_in_stock`(`p_inventory_id` INT) RETURNS tinyint(1)'
            . ' READS SQL DATA'
            . ' COMMENT \'My best function written by a friend\'\'s friend\''
            . ' BEGIN' . "\n"
            . '    DECLARE v_rentals INT;' . "\n"
            . '    DECLARE v_out     INT;' . "\n"
            . "\n"
            . '    ' . "\n"
            . '    ' . "\n"
            . "\n"
            . '    SELECT COUNT(*) INTO v_rentals' . "\n"
            . '    FROM rental' . "\n"
            . '    WHERE inventory_id = p_inventory_id;' . "\n"
            . "\n"
            . '    IF v_rentals = 0 THEN' . "\n"
            . '      RETURN TRUE;' . "\n"
            . '    END IF;' . "\n"
            . "\n"
            . '    SELECT COUNT(rental_id) INTO v_out' . "\n"
            . '    FROM inventory LEFT JOIN rental USING(inventory_id)' . "\n"
            . '    WHERE inventory.inventory_id = p_inventory_id' . "\n"
            . '    AND rental.return_date IS NULL;' . "\n"
            . "\n"
            . '    IF v_out > 0 THEN' . "\n"
            . '      RETURN FALSE;' . "\n"
            . '    ELSE' . "\n"
            . '      RETURN TRUE;' . "\n"
            . '    END IF;' . "\n"
            . 'END'
        );

        /** @var CreateStatement $stmt */
        $stmt = $parser->statements[0];

        $this->assertSame(
            'CREATE DEFINER=`root`@`localhost`'
            . ' FUNCTION `inventory_in_stock` (`p_inventory_id` INT) RETURNS TINYINT(1)'
            . ' READS SQL DATA'
            . ' COMMENT \'My best function written by a friend\'\'s friend\''
            . ' BEGIN' . "\n"
            . '    DECLARE v_rentals INT;' . "\n"
            . '    DECLARE v_out     INT;' . "\n"
            . "\n"
            . '    ' . "\n"
            . '    ' . "\n"
            . "\n"
            . '    SELECT COUNT(*) INTO v_rentals' . "\n"
            . '    FROM rental' . "\n"
            . '    WHERE inventory_id = p_inventory_id;' . "\n"
            . "\n"
            . '    IF v_rentals = 0 THEN' . "\n"
            . '      RETURN TRUE;' . "\n"
            . '    END IF;' . "\n"
            . "\n"
            . '    SELECT COUNT(rental_id) INTO v_out' . "\n"
            . '    FROM inventory LEFT JOIN rental USING(inventory_id)' . "\n"
            . '    WHERE inventory.inventory_id = p_inventory_id' . "\n"
            . '    AND rental.return_date IS NULL;' . "\n"
            . "\n"
            . '    IF v_out > 0 THEN' . "\n"
            . '      RETURN FALSE;' . "\n"
            . '    ELSE' . "\n"
            . '      RETURN TRUE;' . "\n"
            . '    END IF;' . "\n"
            . 'END',
            $stmt->build()
        );

        $this->assertFalse($stmt->entityOptions->isEmpty());
        $this->assertFalse($stmt->options->isEmpty());

        $this->assertSame(
            'DEFINER=`root`@`localhost` FUNCTION',
            $stmt->options->__toString()
        );

        $this->assertSame(
            '`inventory_in_stock`',
            $stmt->name->__toString()
        );

        $this->assertSame(
            '(`p_inventory_id` INT)',
            ParameterDefinition::build($stmt->parameters)
        );

        $this->assertSame(
            'READS SQL DATA COMMENT \'My best function written by a friend\'\'s friend\'',
            $stmt->entityOptions->__toString()
        );

        $this->assertSame(
            'BEGIN' . "\n"
            . '    DECLARE v_rentals INT;' . "\n"
            . '    DECLARE v_out     INT;' . "\n"
            . "\n"
            . '    ' . "\n"
            . '    ' . "\n"
            . "\n"
            . '    SELECT COUNT(*) INTO v_rentals' . "\n"
            . '    FROM rental' . "\n"
            . '    WHERE inventory_id = p_inventory_id;' . "\n"
            . "\n"
            . '    IF v_rentals = 0 THEN' . "\n"
            . '      RETURN TRUE;' . "\n"
            . '    END IF;' . "\n"
            . "\n"
            . '    SELECT COUNT(rental_id) INTO v_out' . "\n"
            . '    FROM inventory LEFT JOIN rental USING(inventory_id)' . "\n"
            . '    WHERE inventory.inventory_id = p_inventory_id' . "\n"
            . '    AND rental.return_date IS NULL;' . "\n"
            . "\n"
            . '    IF v_out > 0 THEN' . "\n"
            . '      RETURN FALSE;' . "\n"
            . '    ELSE' . "\n"
            . '      RETURN TRUE;' . "\n"
            . '    END IF;' . "\n"
            . 'END',
            TokensList::build($stmt->body)
        );
    }

    public function testBuilderTrigger(): void
    {
        $stmt = new CreateStatement();

        $stmt->options = new OptionsArray(['TRIGGER']);
        $stmt->name = new Expression('ins_sum');
        $stmt->entityOptions = new OptionsArray(['BEFORE', 'INSERT']);
        $stmt->table = new Expression('account');
        $stmt->body = 'SET @sum = @sum + NEW.amount';

        $this->assertEquals(
            'CREATE TRIGGER ins_sum BEFORE INSERT ON account ' .
            'FOR EACH ROW SET @sum = @sum + NEW.amount',
            $stmt->build()
        );
    }

    public function testBuilderRoutine(): void
    {
        $parser = new Parser(
            'DELIMITER $$' . "\n" .
            'CREATE FUNCTION test (IN `i` INT) RETURNS VARCHAR ' .
            'BEGIN ' .
            'DECLARE name VARCHAR DEFAULT ""; ' .
            'SELECT name INTO name FROM employees WHERE id = i; ' .
            'RETURN name; ' .
            'END'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'CREATE FUNCTION test (IN `i` INT) RETURNS VARCHAR ' .
            ' BEGIN ' .
            'DECLARE name VARCHAR DEFAULT ""; ' .
            'SELECT name INTO name FROM employees WHERE id = i; ' .
            'RETURN name; ' .
            'END',
            $stmt->build()
        );
    }

    public function testBuildSelect(): void
    {
        $parser = new Parser('CREATE TABLE new_tbl SELECT * FROM orig_tbl');
        $this->assertEquals(
            'CREATE TABLE new_tbl SELECT * FROM orig_tbl',
            $parser->statements[0]->build()
        );
    }

    public function testBuildCreateTableSortedIndex(): void
    {
        $parser = new Parser(
            <<<'SQL'
CREATE TABLE `entries` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `fk_ug_id` int(11) DEFAULT NULL,
    `amount` decimal(10,2) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `entries__ug` (`fk_ug_id` DESC),
    KEY `entries__ug2` (`fk_ug_id` ASC),
    KEY `33` (`id` ASC, `fk_ug_id` DESC)
) /*!50100 TABLESPACE `innodb_system` */ ENGINE=InnoDB AUTO_INCREMENT=4465 DEFAULT CHARSET=utf8
SQL
        );

        /** @var CreateStatement $stmt */
        $stmt = $parser->statements[0];

        $tableBody = <<<'SQL'
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_ug_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entries__ug` (`fk_ug_id` DESC),
  KEY `entries__ug2` (`fk_ug_id` ASC),
  KEY `33` (`id` ASC,`fk_ug_id` DESC)
)
SQL;

        $this->assertEquals(
            $tableBody,
            CreateDefinition::build($stmt->fields)
        );

        $this->assertEquals(
            'CREATE TABLE `entries` '
            . $tableBody
            . ' ENGINE=InnoDB AUTO_INCREMENT=4465 DEFAULT CHARSET=utf8 TABLESPACE `innodb_system`',
            $stmt->build()
        );
    }

    public function testBuildCreateTableComplexIndexes(): void
    {
        // phpcs:disable Generic.Files.LineLength.TooLong
        $parser = new Parser(
            <<<'SQL'
CREATE TABLE `page_rebuild_control` (
    `proc_row_number` int DEFAULT NULL,
    `place_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `place_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `place_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `waterway_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `cache_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `place_active` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `alias_type` int NOT NULL DEFAULT '0',
    `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `time_taken` float DEFAULT NULL,
    PRIMARY KEY (`place_id`,`place_type`) USING BTREE,
    KEY `place_type_idx` (`place_type`(10)),
    KEY `cached_time_idx` (`cache_updated`),
    KEY `active_idx` (`place_active`),
    KEY `status_idx` (`status`),
    KEY `waterway_idx` (`waterway_id`),
    KEY `time_taken_idx` (`time_taken`),
    KEY `updated_tz_ind3` (
        -- my expression
		(convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'GB'))
    ) COMMENT 'foo\'s',
    KEY `updated_tz_ind_two_indexes_commented` (
		-- first expression
		(
			convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'GB')
		)
		,
		-- second expression
		(
			convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'FR')
		)
	)
	-- and now some options
	COMMENT 'haha, this is a complex and indented case',
    KEY `alias_type_idx` (`alias_type`),
    KEY `updated_tz_ind2` ((convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'GB'))) COMMENT 'foo\'s',
    KEY `updated_tz_ind_two_indexes` ((convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'GB')), (convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'FR'))) COMMENT 'bar\'s',
    KEY `updated_tz_ind` ((convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'GB')))
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL
        );
        // phpcs:enable

        /** @var CreateStatement $stmt */
        $stmt = $parser->statements[0];

        // phpcs:disable Generic.Files.LineLength.TooLong
        $tableBody = <<<'SQL'
(
  `proc_row_number` int DEFAULT NULL,
  `place_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `waterway_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cache_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `place_active` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias_type` int NOT NULL DEFAULT '0',
  `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_taken` float DEFAULT NULL,
  PRIMARY KEY (`place_id`,`place_type`) USING BTREE,
  KEY `place_type_idx` (`place_type`(10)),
  KEY `cached_time_idx` (`cache_updated`),
  KEY `active_idx` (`place_active`),
  KEY `status_idx` (`status`),
  KEY `waterway_idx` (`waterway_id`),
  KEY `time_taken_idx` (`time_taken`),
  KEY `updated_tz_ind3` ((convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'GB'))) COMMENT 'foo\'s',
  KEY `updated_tz_ind_two_indexes_commented` ((
			convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'GB')
		), (
			convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'FR')
		)) COMMENT 'haha, this is a complex and indented case',
  KEY `alias_type_idx` (`alias_type`),
  KEY `updated_tz_ind2` ((convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'GB'))) COMMENT 'foo\'s',
  KEY `updated_tz_ind_two_indexes` ((convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'GB')), (convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'FR'))) COMMENT 'bar\'s',
  KEY `updated_tz_ind` ((convert_tz(`cache_updated`,_utf8mb4'GMT',_utf8mb4'GB')))
)
SQL;
        // phpcs:enable

        $this->assertEquals(
            $tableBody,
            CreateDefinition::build($stmt->fields)
        );

        $this->assertEquals(
            'CREATE TABLE `page_rebuild_control` '
            . $tableBody
            . ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
            $stmt->build()
        );
    }
}
