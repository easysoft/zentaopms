<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Lexer;

use PhpMyAdmin\SqlParser\Context;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use Throwable;

use function class_exists;

class ContextTest extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        Context::setMode();
    }

    public function testLoad(): void
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
     * @dataProvider contextLoadingProvider
     */
    public function testLoadClosest(string $context, ?string $expected): void
    {
        $this->assertEquals($expected, Context::loadClosest($context));
        if ($expected !== null) {
            $this->assertEquals('\\PhpMyAdmin\\SqlParser\\Contexts\\Context' . $expected, Context::$loadedContext);
            $this->assertTrue(class_exists(Context::$loadedContext));
        }

        // Restoring context.
        Context::load('');
    }

    /**
     * @return array<string, array<int, string|null>>
     * @psalm-return array<string, array{string, (string|null)}>
     */
    public function contextLoadingProvider(): array
    {
        return [
            'MySQL match' => [
                'MySql50500',
                'MySql50500',
            ],
            'MySQL strip' => [
                'MySql50712',
                'MySql50700',
            ],
            'MySQL fallback' => [
                'MySql99999',
                'MySql50700',
            ],
            'MariaDB match' => [
                'MariaDb100000',
                'MariaDb100000',
            ],
            'MariaDB stripg' => [
                'MariaDb109900',
                'MariaDb100000',
            ],
            'MariaDB fallback' => [
                'MariaDb990000',
                'MariaDb100300',
            ],
            'Invalid' => [
                'Sql',
                null,
            ],
        ];
    }

    /**
     * @dataProvider contextNamesProvider
     */
    public function testLoadAll(string $context): void
    {
        Context::load($context);
        $this->assertEquals('\\PhpMyAdmin\\SqlParser\\Contexts\\Context' . $context, Context::$loadedContext);

        // Restoring context.
        Context::load('');
    }

    /**
     * @return string[][]
     */
    public function contextNamesProvider(): array
    {
        return [
            ['MySql50000'],
            ['MySql50100'],
            ['MySql50500'],
            ['MySql50600'],
            ['MySql50700'],
            ['MySql80000'],
            ['MariaDb100000'],
            ['MariaDb100100'],
            ['MariaDb100200'],
            ['MariaDb100300'],
        ];
    }

    public function testLoadError(): void
    {
        $this->expectExceptionMessage(
            'Specified context ("\PhpMyAdmin\SqlParser\Contexts\ContextFoo") does not exist.'
        );
        $this->expectException(Throwable::class);
        Context::load('Foo');
    }

    /**
     * @param int|string $mode
     *
     * @dataProvider providerForTestMode
     */
    public function testMode($mode, int $expected): void
    {
        Context::setMode($mode);
        $this->assertSame($expected, Context::getMode());
    }

    /**
     * @return array<int, array<int, int|string>>
     * @psalm-return list<array{int|string, int}>
     */
    public function providerForTestMode(): array
    {
        return [
            [0, Context::SQL_MODE_NONE],
            [1, 1],
            ['', Context::SQL_MODE_NONE],
            ['invalid', Context::SQL_MODE_NONE],
            ['ALLOW_INVALID_DATES', Context::SQL_MODE_ALLOW_INVALID_DATES],
            ['ANSI_QUOTES', Context::SQL_MODE_ANSI_QUOTES],
            ['COMPAT_MYSQL', Context::SQL_MODE_COMPAT_MYSQL],
            ['ERROR_FOR_DIVISION_BY_ZERO', Context::SQL_MODE_ERROR_FOR_DIVISION_BY_ZERO],
            ['HIGH_NOT_PRECEDENCE', Context::SQL_MODE_HIGH_NOT_PRECEDENCE],
            ['IGNORE_SPACE', Context::SQL_MODE_IGNORE_SPACE],
            ['NO_AUTO_CREATE_USER', Context::SQL_MODE_NO_AUTO_CREATE_USER],
            ['NO_AUTO_VALUE_ON_ZERO', Context::SQL_MODE_NO_AUTO_VALUE_ON_ZERO],
            ['NO_BACKSLASH_ESCAPES', Context::SQL_MODE_NO_BACKSLASH_ESCAPES],
            ['NO_DIR_IN_CREATE', Context::SQL_MODE_NO_DIR_IN_CREATE],
            ['NO_ENGINE_SUBSTITUTION', Context::SQL_MODE_NO_ENGINE_SUBSTITUTION],
            ['NO_FIELD_OPTIONS', Context::SQL_MODE_NO_FIELD_OPTIONS],
            ['NO_KEY_OPTIONS', Context::SQL_MODE_NO_KEY_OPTIONS],
            ['NO_TABLE_OPTIONS', Context::SQL_MODE_NO_TABLE_OPTIONS],
            ['NO_UNSIGNED_SUBTRACTION', Context::SQL_MODE_NO_UNSIGNED_SUBTRACTION],
            ['NO_ZERO_DATE', Context::SQL_MODE_NO_ZERO_DATE],
            ['NO_ZERO_IN_DATE', Context::SQL_MODE_NO_ZERO_IN_DATE],
            ['ONLY_FULL_GROUP_BY', Context::SQL_MODE_ONLY_FULL_GROUP_BY],
            ['PIPES_AS_CONCAT', Context::SQL_MODE_PIPES_AS_CONCAT],
            ['REAL_AS_FLOAT', Context::SQL_MODE_REAL_AS_FLOAT],
            ['STRICT_ALL_TABLES', Context::SQL_MODE_STRICT_ALL_TABLES],
            ['STRICT_TRANS_TABLES', Context::SQL_MODE_STRICT_TRANS_TABLES],
            ['NO_ENCLOSING_QUOTES', Context::SQL_MODE_NO_ENCLOSING_QUOTES],
            ['ANSI', Context::SQL_MODE_ANSI],
            ['DB2', Context::SQL_MODE_DB2],
            ['MAXDB', Context::SQL_MODE_MAXDB],
            ['MSSQL', Context::SQL_MODE_MSSQL],
            ['ORACLE', Context::SQL_MODE_ORACLE],
            ['POSTGRESQL', Context::SQL_MODE_POSTGRESQL],
            ['TRADITIONAL', Context::SQL_MODE_TRADITIONAL],
        ];
    }

    public function testModeWithCombinedModes(): void
    {
        Context::setMode(
            Context::SQL_MODE_REAL_AS_FLOAT | Context::SQL_MODE_ANSI_QUOTES | Context::SQL_MODE_IGNORE_SPACE
        );
        $this->assertSame(
            Context::SQL_MODE_REAL_AS_FLOAT | Context::SQL_MODE_ANSI_QUOTES | Context::SQL_MODE_IGNORE_SPACE,
            Context::getMode()
        );
        $this->assertTrue(Context::hasMode(Context::SQL_MODE_REAL_AS_FLOAT | Context::SQL_MODE_IGNORE_SPACE));
        $this->assertTrue(Context::hasMode(Context::SQL_MODE_ANSI_QUOTES));
        $this->assertFalse(Context::hasMode(Context::SQL_MODE_REAL_AS_FLOAT | Context::SQL_MODE_ALLOW_INVALID_DATES));
        $this->assertFalse(Context::hasMode(Context::SQL_MODE_ALLOW_INVALID_DATES));

        Context::setMode(Context::SQL_MODE_TRADITIONAL);
        $this->assertSame(Context::SQL_MODE_TRADITIONAL, Context::getMode());

        Context::setMode();
        $this->assertSame(Context::SQL_MODE_NONE, Context::getMode());
    }

    public function testModeWithString(): void
    {
        Context::setMode('REAL_AS_FLOAT,ANSI_QUOTES,IGNORE_SPACE');
        $this->assertSame(
            Context::SQL_MODE_REAL_AS_FLOAT | Context::SQL_MODE_ANSI_QUOTES | Context::SQL_MODE_IGNORE_SPACE,
            Context::getMode()
        );
        $this->assertTrue(Context::hasMode(Context::SQL_MODE_REAL_AS_FLOAT | Context::SQL_MODE_IGNORE_SPACE));
        $this->assertTrue(Context::hasMode(Context::SQL_MODE_ANSI_QUOTES));
        $this->assertFalse(Context::hasMode(Context::SQL_MODE_REAL_AS_FLOAT | Context::SQL_MODE_ALLOW_INVALID_DATES));
        $this->assertFalse(Context::hasMode(Context::SQL_MODE_ALLOW_INVALID_DATES));

        Context::setMode('TRADITIONAL');
        $this->assertSame(Context::SQL_MODE_TRADITIONAL, Context::getMode());

        Context::setMode('');
        $this->assertSame(Context::SQL_MODE_NONE, Context::getMode());
    }

    public function testEscape(): void
    {
        Context::setMode(Context::SQL_MODE_NO_ENCLOSING_QUOTES);
        $this->assertEquals('test', Context::escape('test'));

        Context::setMode(Context::SQL_MODE_ANSI_QUOTES);
        $this->assertEquals('"test"', Context::escape('test'));

        Context::setMode();
        $this->assertEquals('`test`', Context::escape('test'));

        $this->assertEquals(['`a`', '`b`'], Context::escape(['a', 'b']));
    }
}
