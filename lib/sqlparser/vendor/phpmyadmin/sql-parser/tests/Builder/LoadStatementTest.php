<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class LoadStatementTest extends TestCase
{
    public function testBuilder(): void
    {
        /* Assertion 1 */
        $query = 'LOAD DATA CONCURRENT INFILE '
            . '\'employee1.txt\' INTO TABLE employee';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'LOAD DATA CONCURRENT INFILE '
            . '\'employee1.txt\' INTO TABLE employee',
            $stmt->build()
        );

        /* Assertion 2 */
        $query = 'LOAD DATA INFILE \'/tmp/test.txt\' '
            . 'INTO TABLE test FIELDS TERMINATED BY '
            . '\',\' IGNORE 1 LINES';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'LOAD DATA  INFILE \'/tmp/test.txt\' '
            . 'INTO TABLE test FIELDS TERMINATED BY '
            . '\',\' IGNORE 1 LINES',
            $stmt->build()
        );

        /* Assertion 3 */
        $query = 'LOAD DATA INFILE \'employee3.txt\' '
            . 'INTO TABLE employee FIELDS TERMINATED BY '
            . '\',\' ENCLOSED BY \'"\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'LOAD DATA  INFILE \'employee3.txt\' '
            . 'INTO TABLE employee FIELDS TERMINATED BY '
            . '\',\' ENCLOSED BY \'"\'',
            $stmt->build()
        );

        /* Assertion 4 */
        $query = 'LOAD DATA INFILE \'/tmp/test.txt\' IGNORE '
            . 'INTO TABLE test '
            . 'CHARACTER SET \'utf8\' '
            . 'COLUMNS TERMINATED BY \',\' '
            . 'LINES TERMINATED BY \';\' '
            . 'IGNORE 1 LINES (col1, col2) SET @a = 1';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'LOAD DATA  INFILE \'/tmp/test.txt\' IGNORE '
            . 'INTO TABLE test '
            . 'CHARACTER SET \'utf8\' '
            . 'COLUMNS TERMINATED BY \',\' '
            . 'LINES TERMINATED BY \';\' '
            . 'IGNORE 1 LINES (col1, col2) SET @a = 1',
            $stmt->build()
        );

        /* Assertion 5 */
        $query = 'LOAD DATA INFILE \'/tmp/test.txt\' REPLACE '
            . 'INTO TABLE test COLUMNS TERMINATED BY \',\' IGNORE 1 ROWS';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'LOAD DATA  INFILE \'/tmp/test.txt\' REPLACE '
            . 'INTO TABLE test COLUMNS TERMINATED BY \',\' IGNORE 1 ROWS',
            $stmt->build()
        );

        /* Assertion 6 */
        $query = 'LOAD DATA INFILE \'/tmp/test.txt\' IGNORE '
            . 'INTO TABLE test PARTITION (p0, p1, p2) CHARACTER SET \'utf8\' '
            . 'COLUMNS TERMINATED BY \',\' LINES TERMINATED BY \';\' '
            . 'IGNORE 1 LINES (col1, col2) SET @a = 1';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'LOAD DATA  INFILE \'/tmp/test.txt\' IGNORE '
            . 'INTO TABLE test PARTITION (p0, p1, p2) CHARACTER SET \'utf8\' '
            . 'COLUMNS TERMINATED BY \',\' LINES TERMINATED BY \';\' '
            . 'IGNORE 1 LINES (col1, col2) SET @a = 1',
            $stmt->build()
        );
    }
}
