<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ExpressionTest extends TestCase
{
    public function testParse()
    {
        $component = Expression::parse(new Parser(), $this->getTokensList('IF(film_id > 0, film_id, film_id)'));
        $this->assertEquals($component->expr, 'IF(film_id > 0, film_id, film_id)');
    }

    public function testParse2()
    {
        $component = Expression::parse(new Parser(), $this->getTokensList('col`test`'));
        $this->assertEquals($component->expr, 'col');
    }

    /**
     * @dataProvider parseErrProvider
     *
     * @param mixed $expr
     * @param mixed $error
     */
    public function testParseErr($expr, $error)
    {
        $parser = new Parser();
        Expression::parse($parser, $this->getTokensList($expr));
        $errors = $this->getErrorsAsArray($parser);
        $this->assertEquals($errors[0][0], $error);
    }

    public function parseErrProvider()
    {
        return array(
            /*
            array(
                '(1))',
                'Unexpected closing bracket.',
            ),
            */
            array(
                'tbl..col',
                'Unexpected dot.',
            ),
            array(
                'id AS AS id2',
                'An alias was expected.',
            ),
            array(
                'id`id2`\'id3\'',
                'An alias was previously found.',
            ),
            array(
                '(id) id2 id3',
                'An alias was previously found.',
            )
        );
    }

    public function testBuild()
    {
        $component = array(
            new Expression('1 + 2', 'three'),
            new Expression('1 + 3', 'four')
        );
        $this->assertEquals(
            Expression::build($component),
            '1 + 2 AS `three`, 1 + 3 AS `four`'
        );
    }
}
