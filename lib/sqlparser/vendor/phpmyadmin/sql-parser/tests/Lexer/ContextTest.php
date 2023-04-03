<?php

namespace PhpMyAdmin\SqlParser\Tests\Lexer;

use PhpMyAdmin\SqlParser\Context;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ContextTest extends TestCase
{
    public function testLoad()
    {
        // Default context is 5.7.0.
        $this->assertEquals('\\PhpMyAdmin\\SqlParser\\Contexts\\ContextMySql50700', Context::$loadedContext);
        $this->assertArrayHasKey('STORED', Context::$KEYWORDS);
        $this->assertArrayNotHasKey('AUTHORS', Context::$KEYWORDS);

        // Restoring context.
        Context::load('');
        $this->assertEquals('\\PhpMyAdmin\\SqlParser\\Contexts\\ContextMySql50700', Context::$defaultContext);
        $this->assertArrayHasKey('STORED', Context::$KEYWORDS);
        $this->assertArrayNotHasKey('AUTHORS', Context::$KEYWORDS);
    }

    /**
     * Test for loading closest SQL context
     *
     * @dataProvider contextLoading
     */
    public function testLoadClosest($context, $expected)
    {
        $this->assertEquals($expected, Context::loadClosest($context));
        if (! is_null($expected)) {
            $this->assertEquals('\\PhpMyAdmin\\SqlParser\\Contexts\\Context' . $expected, Context::$loadedContext);
            $this->assertTrue(class_exists(Context::$loadedContext));
        }

        // Restoring context.
        Context::load('');
    }

    public function contextLoading()
    {
        return array(
            'MySQL match' => array(
                'MySql50500',
                'MySql50500',
            ),
            'MySQL strip' => array(
                'MySql50712',
                'MySql50700',
            ),
            'MySQL fallback' => array(
                'MySql99999',
                'MySql50700',
            ),
            'MariaDB match' => array(
                'MariaDb100000',
                'MariaDb100000',
            ),
            'MariaDB stripg' => array(
                'MariaDb109900',
                'MariaDb100000',
            ),
            'MariaDB fallback' => array(
                'MariaDb990000',
                'MariaDb100300',
            ),
            'Invalid' => array(
                'Sql',
                null,
            )
        );
    }

    /**
     * @dataProvider contextNames
     *
     * @param mixed $context
     */
    public function testLoadAll($context)
    {
        Context::load($context);
        $this->assertEquals('\\PhpMyAdmin\\SqlParser\\Contexts\\Context' . $context, Context::$loadedContext);

        // Restoring context.
        Context::load('');
    }

    public function contextNames()
    {
        return array(
            array('MySql50000'),
            array('MySql50100'),
            array('MySql50500'),
            array('MySql50600'),
            array('MySql50700'),
            array('MySql80000'),
            array('MariaDb100000'),
            array('MariaDb100100'),
            array('MariaDb100200'),
            array('MariaDb100300')
        );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Specified context ("\PhpMyAdmin\SqlParser\Contexts\ContextFoo") does not exist.
     */
    public function testLoadError()
    {
        Context::load('Foo');
    }

    public function testMode()
    {
        Context::setMode('REAL_AS_FLOAT,ANSI_QUOTES,IGNORE_SPACE');
        $this->assertEquals(
            Context::SQL_MODE_REAL_AS_FLOAT | Context::SQL_MODE_ANSI_QUOTES | Context::SQL_MODE_IGNORE_SPACE,
            Context::$MODE
        );
        Context::setMode('TRADITIONAL');
        $this->assertEquals(
            Context::SQL_MODE_TRADITIONAL,
            Context::$MODE
        );
        Context::setMode();
        $this->assertEquals(0, Context::$MODE);
    }

    public function testEscape()
    {
        Context::setMode('NO_ENCLOSING_QUOTES');
        $this->assertEquals('test', Context::escape('test'));

        Context::setMode('ANSI_QUOTES');
        $this->assertEquals('"test"', Context::escape('test'));

        Context::setMode();
        $this->assertEquals('`test`', Context::escape('test'));

        $this->assertEquals(
            array(
                '`a`',
                '`b`',
            ),
            Context::escape(array('a', 'b'))
        );
    }
}
