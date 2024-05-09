<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ExpressionTest extends TestCase
{
    public function testParse(): void
    {
        $component = Expression::parse(new Parser(), $this->getTokensList('IF(film_id > 0, film_id, film_id)'));
        $this->assertEquals($component->expr, 'IF(film_id > 0, film_id, film_id)');
    }

    public function testParse2(): void
    {
        $component = Expression::parse(new Parser(), $this->getTokensList('col`test`'));
        $this->assertEquals($component->expr, 'col');
    }

    public function testParse3(): void
    {
        $component = Expression::parse(new Parser(), $this->getTokensList('col xx'));
        $this->assertEquals($component->alias, 'xx');

        $component = Expression::parse(new Parser(), $this->getTokensList('col y'));
        $this->assertEquals($component->alias, 'y');

        $component = Expression::parse(new Parser(), $this->getTokensList('avg.col FROM (SELECT ev.col FROM ev)'));
        $this->assertEquals($component->table, 'avg');
        $this->assertEquals($component->expr, 'avg.col');

        $component = Expression::parse(new Parser(), $this->getTokensList('x.id FROM (SELECT a.id FROM a) x'));
        $this->assertEquals($component->table, 'x');
        $this->assertEquals($component->expr, 'x.id');
    }

    /**
     * @dataProvider parseErrProvider
     */
    public function testParseErr(string $expr, string $error): void
    {
        $parser = new Parser();
        Expression::parse($parser, $this->getTokensList($expr));
        $errors = $this->getErrorsAsArray($parser);
        $this->assertEquals($errors[0][0], $error);
    }

    /**
     * @return string[][]
     */
    public function parseErrProvider(): array
    {
        return [
            /*
            [
                '(1))',
                'Unexpected closing bracket.',
            ],
            */
            [
                'tbl..col',
                'Unexpected dot.',
            ],
            [
                'id AS AS id2',
                'An alias was expected.',
            ],
            [
                'id`id2`\'id3\'',
                'An alias was previously found.',
            ],
            [
                '(id) id2 id3',
                'An alias was previously found.',
            ],
        ];
    }

    public function testBuild(): void
    {
        $component = [
            new Expression('1 + 2', 'three'),
            new Expression('1 + 3', 'four'),
        ];
        $this->assertEquals(
            Expression::build($component),
            '1 + 2 AS `three`, 1 + 3 AS `four`'
        );
    }

    /**
     * @return string[][]
     */
    public static function mysqlCommandsProvider(): array
    {
        return [
            [
                '/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;',
                'SET  @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT',
            ],
            [
                '/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;',
                'SET  @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS',
            ],
            [
                '/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;',
                'SET  @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION',
            ],
            [
                '/*!40101 SET NAMES utf8 */;',
                'SET NAMES utf8',
            ],
            [
                '/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;',
                'SET  @OLD_TIME_ZONE = @@TIME_ZONE',
            ],
            [
                "/*!40103 SET TIME_ZONE='+00:00' */;",
                "SET  TIME_ZONE = '+00:00'",
            ],
            [
                '/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;',
                'SET  @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0',
            ],
            [
                '/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;',
                'SET  @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0',
            ],
            [
                "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;",
                "SET  @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'",
            ],
            [
                '/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;',
                'SET  @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0',
            ],
        ];
    }

    /**
     * @dataProvider mysqlCommandsProvider
     */
    public function testMysqlCommands(string $expr, string $expected): void
    {
        $parser = new Parser($expr, true);
        $parser->parse();
        self::assertSame($expected, $parser->statements[0]->build());
    }
}
