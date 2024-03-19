<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use Generator;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class AlterStatementTest extends TestCase
{
    public function testBuilder(): void
    {
        $query = 'ALTER TABLE `actor` ' .
            'ADD PRIMARY KEY (`actor_id`), ' .
            'ADD KEY `idx_actor_last_name` (`last_name`)';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }

    public function testBuilderWithExpression(): void
    {
        $query = 'ALTER TABLE `table` '
                . 'ADD UNIQUE KEY `functional_index`'
                . ' (`field1`,`field2`, (IFNULL(`field3`,0)))';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }

    public function testBuilderWithComments(): void
    {
        $query = 'ALTER /* comment */ TABLE `actor` ' .
            'ADD PRIMARY KEY (`actor_id`), -- comment at the end of the line' . "\n" .
            'ADD KEY `idx_actor_last_name` (`last_name`) -- and that is the last comment.';

        $expectedQuery = 'ALTER TABLE `actor` ' .
            'ADD PRIMARY KEY (`actor_id`), ' .
            'ADD KEY `idx_actor_last_name` (`last_name`)';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($expectedQuery, $stmt->build());
    }

    public function testBuilderWithCommentsOnOptions(): void
    {
        $query = 'ALTER EVENT `myEvent` /* comment */ ' .
            'ON SCHEDULE -- Comment at the end of the line' . "\n" .
            'AT "2023-01-01 01:23:45"';

        $expectedQuery = 'ALTER EVENT `myEvent` ' .
            'ON SCHEDULE AT "2023-01-01 01:23:45"';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($expectedQuery, $stmt->build());
    }

    public function testBuilderCompressed(): void
    {
        $query = 'ALTER TABLE `user` CHANGE `message` `message` TEXT COMPRESSED';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals($query, $stmt->build());
    }

    public function testBuilderPartitions(): void
    {
        $parser = new Parser('ALTER TABLE t1 PARTITION BY HASH(id) PARTITIONS 8');
        $stmt = $parser->statements[0];

        $this->assertEquals('ALTER TABLE t1 PARTITION BY  HASH(id) PARTITIONS 8', $stmt->build());

        $parser = new Parser('ALTER TABLE t1 ADD PARTITION (PARTITION p3 VALUES LESS THAN (2002))');
        $stmt = $parser->statements[0];

        $this->assertEquals(
            "ALTER TABLE t1 ADD PARTITION (\n" .
            "PARTITION p3 VALUES LESS THAN (2002)\n" .
            ')',
            $stmt->build()
        );

        $parser = new Parser('ALTER TABLE p PARTITION BY LINEAR KEY ALGORITHM=2 (id) PARTITIONS 32;');
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'ALTER TABLE p PARTITION BY  LINEAR KEY ALGORITHM=2 (id) PARTITIONS 32',
            $stmt->build()
        );

        $parser = new Parser('ALTER TABLE t1 DROP PARTITION p0, p1;');
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'ALTER TABLE t1 DROP PARTITION  p0, p1',
            $stmt->build()
        );

        $parser = new Parser(
            'ALTER TABLE trips PARTITION BY RANGE (MONTH(trip_date))'
            . ' (' . "\n"
            . ' PARTITION p01 VALUES LESS THAN (02),' . "\n"
            . ' PARTITION p02 VALUES LESS THAN (03),' . "\n"
            . ' PARTITION p03 VALUES LESS THAN (04),' . "\n"
            . ' PARTITION p04 VALUES LESS THAN (05),' . "\n"
            . ' PARTITION p05 VALUES LESS THAN (06),' . "\n"
            . ' PARTITION p06 VALUES LESS THAN (07),' . "\n"
            . ' PARTITION p07 VALUES LESS THAN (08),' . "\n"
            . ' PARTITION p08 VALUES LESS THAN (09),' . "\n"
            . ' PARTITION p09 VALUES LESS THAN (10),' . "\n"
            . ' PARTITION p10 VALUES LESS THAN (11),' . "\n"
            . ' PARTITION p11 VALUES LESS THAN (12),' . "\n"
            . ' PARTITION p12 VALUES LESS THAN (13),' . "\n"
            . ' PARTITION pmaxval VALUES LESS THAN MAXVALUE' . "\n"
            . ');'
        );
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'ALTER TABLE trips PARTITION BY  RANGE (MONTH(trip_date))  (' . "\n"
            . 'PARTITION p01 VALUES LESS THAN (02),' . "\n"
            . 'PARTITION p02 VALUES LESS THAN (03),' . "\n"
            . 'PARTITION p03 VALUES LESS THAN (04),' . "\n"
            . 'PARTITION p04 VALUES LESS THAN (05),' . "\n"
            . 'PARTITION p05 VALUES LESS THAN (06),' . "\n"
            . 'PARTITION p06 VALUES LESS THAN (07),' . "\n"
            . 'PARTITION p07 VALUES LESS THAN (08),' . "\n"
            . 'PARTITION p08 VALUES LESS THAN (09),' . "\n"
            . 'PARTITION p09 VALUES LESS THAN (10),' . "\n"
            . 'PARTITION p10 VALUES LESS THAN (11),' . "\n"
            . 'PARTITION p11 VALUES LESS THAN (12),' . "\n"
            . 'PARTITION p12 VALUES LESS THAN (13),' . "\n"
            . 'PARTITION pmaxval VALUES LESS THAN MAXVALUE' . "\n"
            . ')',
            $stmt->build()
        );
    }

    public function testBuilderEventWithDefiner(): void
    {
        $query = 'ALTER DEFINER=user EVENT myEvent ENABLE';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals($query, $stmt->build());
    }

    /**
     * @return Generator<string, array{string}>
     */
    public static function provideBuilderForRenameColumn(): Generator
    {
        $query = 'ALTER TABLE myTable RENAME COLUMN a TO b';

        yield 'Single RENAME COLUMN' => [$query];

        $query = 'ALTER TABLE myTable RENAME COLUMN a TO b, RENAME COLUMN b TO a';

        yield 'Multiple RENAME COLUMN' => [$query];

        $query = 'ALTER TABLE myTable ' .
            'RENAME COLUMN a TO b, ' .
            'RENAME COLUMN b TO a, ' .
            'RENAME INDEX oldIndex TO newIndex, ' .
            'RENAME TO newTable';

        yield 'Mixed RENAME COLUMN + RENAME INDEX + RENAME table' => [$query];

        $query = 'ALTER TABLE myTable ' .
            'RENAME TO newTable, ' .
            'RENAME INDEX oldIndex TO newIndex, ' .
            'RENAME COLUMN b TO a, ' .
            'RENAME COLUMN a TO b';

        yield 'Mixed RENAME table + RENAME INDEX + RENAME COLUMNS' => [$query];
    }

    /**
     * @dataProvider provideBuilderForRenameColumn
     */
    public function testBuilderRenameColumn(string $query): void
    {
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals($query, $stmt->build());
    }
}
