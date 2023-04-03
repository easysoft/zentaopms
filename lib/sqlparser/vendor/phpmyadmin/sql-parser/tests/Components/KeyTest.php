<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Components\Key;
use PhpMyAdmin\SqlParser\Components\OptionsArray;
use PhpMyAdmin\SqlParser\Exceptions\ParserException;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Token;

class KeyTest extends TestCase
{
    public function testParse()
    {
        $component = Key::parse(
            new Parser(),
            $this->getTokensList('')
        );
        $this->assertNull($component->type);
        $this->assertNull($component->options);
        $this->assertNull($component->name);
        $this->assertNull($component->expr);
        $this->assertSame(array(), $component->columns);
        $this->assertSame(
            '()',
            Key::build($component)
        );
    }

    public function testParseKeyWithoutOptions()
    {
        $component = Key::parse(
            new Parser(),
            $this->getTokensList('KEY `alias_type_idx` (`alias_type`),')
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('alias_type_idx', $component->name);
        $this->assertEquals(new OptionsArray(), $component->options);
        $this->assertNull($component->expr);
        $this->assertSame(array(
            array(
                'name' => 'alias_type',
            )
        ), $component->columns);
        $this->assertSame(
            'KEY `alias_type_idx` (`alias_type`)',
            Key::build($component)
        );
    }

    public function testParseKeyWithLengthWithoutOptions()
    {
        $component = Key::parse(
            new Parser(),
            $this->getTokensList('KEY `alias_type_idx` (`alias_type`(10)),')
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('alias_type_idx', $component->name);
        $this->assertEquals(new OptionsArray(), $component->options);
        $this->assertNull($component->expr);
        $this->assertSame(array(
            array(
                'name' => 'alias_type',
                'length' => 10,
            )
        ), $component->columns);
        $this->assertSame(
            'KEY `alias_type_idx` (`alias_type`(10))',
            Key::build($component)
        );
    }

    public function testParseKeyWithLengthWithoutOptionsWithOrder()
    {
        $component = Key::parse(
            new Parser(),
            $this->getTokensList('KEY `alias_type_idx` (`alias_type`(10) ASC),')
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('alias_type_idx', $component->name);
        $this->assertEquals(new OptionsArray(), $component->options);
        $this->assertNull($component->expr);
        $this->assertSame(array(
            array(
                'name' => 'alias_type',
                'length' => 10,
                'order' => 'ASC',
            )
        ), $component->columns);
        $this->assertSame(
            'KEY `alias_type_idx` (`alias_type`(10) ASC)',
            Key::build($component)
        );
    }

    public function testParseKeyWithoutOptionsWithOrderLowercase()
    {
        $component = Key::parse(
            new Parser(),
            $this->getTokensList('KEY `alias_type_idx` (`alias_type` desc),')
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('alias_type_idx', $component->name);
        $this->assertEquals(new OptionsArray(), $component->options);
        $this->assertNull($component->expr);
        $this->assertSame(array(
            array(
                'name' => 'alias_type',
                'order' => 'DESC',
            )
        ), $component->columns);
        $this->assertSame(
            'KEY `alias_type_idx` (`alias_type` DESC)',
            Key::build($component)
        );
    }

    public function testParseKeyWithoutOptionsWithOrder()
    {
        $component = Key::parse(
            new Parser(),
            $this->getTokensList('KEY `alias_type_idx` (`alias_type` DESC),')
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('alias_type_idx', $component->name);
        $this->assertEquals(new OptionsArray(), $component->options);
        $this->assertNull($component->expr);
        $this->assertSame(array(
            array(
                'name' => 'alias_type',
                'order' => 'DESC',
            )
        ), $component->columns);
        $this->assertSame(
            'KEY `alias_type_idx` (`alias_type` DESC)',
            Key::build($component)
        );
    }

    public function testParseKeyWithLengthWithOptions()
    {
        $component = Key::parse(
            new Parser(),
            $this->getTokensList('KEY `alias_type_idx` (`alias_type`(10)) COMMENT \'my comment\',')
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('alias_type_idx', $component->name);
        $this->assertEquals(new OptionsArray(
            array(
                4 => array(
                    'name' => 'COMMENT',
                    'equals' => false,
                    'expr' => '\'my comment\'',
                    'value' => 'my comment',
                )
            )
        ), $component->options);
        $this->assertNull($component->expr);
        $this->assertSame(array(
            array(
                'name' => 'alias_type',
                'length' => 10,
            )
        ), $component->columns);
        $this->assertSame(
            'KEY `alias_type_idx` (`alias_type`(10)) COMMENT \'my comment\'',
            Key::build($component)
        );
    }

    public function testParseKeyWithLengthWithAllOptions()
    {
        $component = Key::parse(
            new Parser(),
            $this->getTokensList(
                // This is not a vary plausible example but it runs
                // Only ENGINE_ATTRIBUTE gives a not supported error but is still a valid syntax
                'KEY `alias_type_idx` (`alias_type`(10))'
                . ' COMMENT \'my comment\' VISIBLE KEY_BLOCK_SIZE=1'
                . ' INVISIBLE ENGINE_ATTRIBUTE \'foo\' SECONDARY_ENGINE_ATTRIBUTE=\'bar\' USING BTREE,'
            )
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('alias_type_idx', $component->name);
        $this->assertEquals(new OptionsArray(
            array(
                1 => array(
                    'name' => 'KEY_BLOCK_SIZE',
                    'equals' => true,
                    'expr' => '1',
                    'value' => '1',
                ),
                2 => array(
                    'name' => 'USING',
                    'equals' => false,
                    'expr' => 'BTREE',
                    'value' => 'BTREE',
                ),
                4 => array(
                    'name' => 'COMMENT',
                    'equals' => false,
                    'expr' => '\'my comment\'',
                    'value' => 'my comment',
                ),
                5 => array(
                    'name' => 'ENGINE_ATTRIBUTE',
                    'equals' => true,
                    'expr' => '\'foo\'',
                    'value' => 'foo',
                ),
                6 => 'VISIBLE',
                12 => 'INVISIBLE',
                13 => array(
                    'name' => 'SECONDARY_ENGINE_ATTRIBUTE',
                    'equals' => true,
                    'expr' => '\'bar\'',
                    'value' => 'bar',
                ),
            )
        ), $component->options);
        $this->assertNull($component->expr);
        $this->assertSame(array(
            array(
                'name' => 'alias_type',
                'length' => 10,
            )
        ), $component->columns);
    }

    public function testParseKeyExpressionWithoutOptions()
    {
        $component = Key::parse(
            new Parser(),
            $this->getTokensList(
                'KEY `updated_tz_ind2` ((convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\'))),'
            )
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('updated_tz_ind2', $component->name);
        $this->assertEquals(new OptionsArray(), $component->options);
        $expr = new Expression(
            '(convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\'))'
        );
        $expr->function = 'convert_tz';
        $this->assertEquals(
            $expr,
            $component->expr
        );
        $this->assertSame(array(), $component->columns);
        $this->assertSame(
            'KEY `updated_tz_ind2` ((convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\'))) ',
            Key::build($component)
        );
    }

    public function testParseKeyExpressionWithOptions()
    {
        $component = Key::parse(
            new Parser(),
            $this->getTokensList(
                'KEY `updated_tz_ind2`'
                . ' ((convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\')))'
                . ' COMMENT \'my comment\','
            )
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('updated_tz_ind2', $component->name);
        $this->assertEquals(new OptionsArray(
            array(
                4 => array(
                    'name' => 'COMMENT',
                    'equals' => false,
                    'expr' => '\'my comment\'',
                    'value' => 'my comment',
                )
            )
        ), $component->options);
        $expr = new Expression(
            '(convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\'))'
        );
        $expr->function = 'convert_tz';
        $this->assertEquals(
            $expr,
            $component->expr
        );
        $this->assertSame(array(), $component->columns);
        $this->assertSame(
            'KEY `updated_tz_ind2`'
            . ' ((convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\')))'
            . ' COMMENT \'my comment\'',
            Key::build($component)
        );
    }

    public function testParseKeyExpressionWithOptionsError()
    {
        $parser = new Parser();
        $component = Key::parse(
            $parser,
            $this->getTokensList(
                'KEY `updated_tz_ind2` (()convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\')))'
                . ' COMMENT \'my comment\','
            )
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('updated_tz_ind2', $component->name);
        $this->assertEquals(new OptionsArray(
            array(
            )
        ), $component->options);
        $t = new Token(
            'convert_tz',
            Token::TYPE_KEYWORD,
            33
        );
        $t->position = 25;

        $this->assertEquals(array(
            new ParserException(
                'Unexpected token.',
                $t
            )
        ), $parser->errors);
        $expr = new Expression(
            '(convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\'))'
        );
        $expr->function = 'convert_tz';
        $this->assertEquals(
            '()(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\')',
            $component->expr
        );
        $this->assertSame(array(), $component->columns);
        $this->assertSame(
            'KEY `updated_tz_ind2` (()(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\')) ',
            Key::build($component)
        );
    }

    public function testParseKeyOneExpressionWithOptions()
    {
        $parser = new Parser();
        $component = Key::parse(
            $parser,
            $this->getTokensList(
                'KEY `updated_tz_ind2`'
                . ' ('
                . '(convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\')), '
                . '(convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'FR\'))'
                . ')'
                . ' COMMENT \'my comment\','
            )
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('updated_tz_ind2', $component->name);
        $this->assertEquals(new OptionsArray(
            array(
                4 => array(
                    'name' => 'COMMENT',
                    'equals' => false,
                    'expr' => '\'my comment\'',
                    'value' => 'my comment',
                )
            )
        ), $component->options);
        $this->assertSame(array(), $parser->errors);
        $expr = new Expression(
            '(convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\')),'
            . ' (convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'FR\'))'
        );
        $expr->function = 'convert_tz';
        $this->assertEquals(
            $expr,
            $component->expr
        );
        $this->assertSame(array(), $component->columns);
        $this->assertSame(
            'KEY `updated_tz_ind2` ((convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\')),'
            . ' (convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'FR\'))'
            . ') COMMENT \'my comment\'',
            Key::build($component)
        );
    }

    public function testParseKeyMultipleExpressionsWithOptions()
    {
        $parser = new Parser();
        $component = Key::parse(
            $parser,
            $this->getTokensList(
                'KEY `updated_tz_ind2`'
                . ' ('
                . '(convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\')), '
                . '(convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'FR\')), '
                . '(convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'RU\'))'
                . ')'
                . ' COMMENT \'my comment\','
            )
        );
        $this->assertEquals('KEY', $component->type);
        $this->assertEquals('updated_tz_ind2', $component->name);
        $this->assertEquals(new OptionsArray(
            array(
                4 => array(
                    'name' => 'COMMENT',
                    'equals' => false,
                    'expr' => '\'my comment\'',
                    'value' => 'my comment',
                )
            )
        ), $component->options);
        $expr = new Expression(
            '(convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\')),'
            . ' (convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'FR\')),'
            . ' (convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'RU\'))'
        );
        $expr->function = 'convert_tz';
        $this->assertEquals(
            $expr,
            $component->expr
        );
        $this->assertSame(array(), $component->columns);
        $this->assertSame(array(), $parser->errors);
        $this->assertSame(
            'KEY `updated_tz_ind2` ((convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'GB\')),'
            . ' (convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'FR\')),'
            . ' (convert_tz(`cache_updated`,_utf8mb4\'GMT\',_utf8mb4\'RU\'))'
            . ') COMMENT \'my comment\'',
            Key::build($component)
        );
    }
}
