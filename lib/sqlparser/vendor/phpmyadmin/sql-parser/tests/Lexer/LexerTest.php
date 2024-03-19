<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Lexer;

use PhpMyAdmin\SqlParser\Exceptions\LexerException;
use PhpMyAdmin\SqlParser\Lexer;
use PhpMyAdmin\SqlParser\Tests\TestCase;

use function sprintf;

class LexerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testError(): void
    {
        $lexer = new Lexer('');

        $lexer->error('error #1', 'foo', 1, 2);
        $lexer->error(
            sprintf('%2$s #%1$d', 2, 'error'),
            'bar',
            3,
            4
        );

        $this->assertEquals(
            $lexer->errors,
            [
                new LexerException('error #1', 'foo', 1, 2),
                new LexerException('error #2', 'bar', 3, 4),
            ]
        );
    }

    public function testErrorStrict(): void
    {
        $this->expectExceptionCode(4);
        $this->expectExceptionMessage('strict error');
        $this->expectException(LexerException::class);
        $lexer = new Lexer('');
        $lexer->strict = true;

        $lexer->error('strict error', 'foo', 1, 4);
    }

    /**
     * @dataProvider lexProvider
     */
    public function testLex(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function lexProvider(): array
    {
        return [
            ['lexer/lex'],
            ['lexer/lexUtf8'],
            ['lexer/lexBool'],
            ['lexer/lexComment'],
            ['lexer/lexCommentEnd'],
            ['lexer/lexDelimiter'],
            ['lexer/lexDelimiter2'],
            ['lexer/lexDelimiterErr1'],
            ['lexer/lexDelimiterErr2'],
            ['lexer/lexDelimiterErr3'],
            ['lexer/lexDelimiterLen'],
            ['lexer/lexKeyword'],
            ['lexer/lexKeyword2'],
            ['lexer/lexNumber'],
            ['lexer/lexOperator'],
            ['lexer/lexOperatorStarIsArithmetic'],
            ['lexer/lexOperatorStarIsWildcard'],
            ['lexer/lexString'],
            ['lexer/lexStringErr1'],
            ['lexer/lexSymbol'],
            ['lexer/lexSymbolErr1'],
            ['lexer/lexSymbolErr2'],
            ['lexer/lexSymbolErr3'],
            ['lexer/lexSymbolUser1'],
            ['lexer/lexSymbolUser2'],
            ['lexer/lexSymbolUser3'],
            ['lexer/lexSymbolUser4_mariadb_100400'],
            ['lexer/lexSymbolUser5_mariadb_100400'],
            ['lexer/lexWhitespace'],
            ['lexer/lexLabel1'],
            ['lexer/lexLabel2'],
            ['lexer/lexNoLabel'],
            ['lexer/lexWildcardThenComment'],
        ];
    }
}
