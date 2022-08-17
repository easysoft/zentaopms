<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Utils;

use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Token;
use PhpMyAdmin\SqlParser\Utils\Tokens;

class TokensTest extends TestCase
{
    /**
     * @param mixed $list
     * @param mixed $find
     * @param mixed $replace
     * @param mixed $expected
     *
     * @dataProvider replaceTokensProvider
     */
    public function testReplaceTokens($list, $find, $replace, $expected): void
    {
        $this->assertEquals($expected, Tokens::replaceTokens($list, $find, $replace));
    }

    public function replaceTokensProvider(): array
    {
        return [
            [
                'SELECT * FROM /*x*/a/*c*/.b',
                [
                    ['value_str' => 'a'],
                    ['token' => '.'],
                ],
                [
                    new Token('c'),
                    new Token('.'),
                ],
                'SELECT * FROM /*x*/c.b',
            ],
        ];
    }

    /**
     * @param mixed $token
     * @param mixed $pattern
     * @param mixed $expected
     *
     * @dataProvider matchProvider
     */
    public function testMatch($token, $pattern, $expected): void
    {
        $this->assertEquals($expected, Tokens::match($token, $pattern));
    }

    public function matchProvider(): array
    {
        return [
            [
                new Token(''),
                [],
                true,
            ],

            [
                new Token('"abc"', Token::TYPE_STRING, Token::FLAG_STRING_DOUBLE_QUOTES),
                ['token' => '"abc"'],
                true,
            ],
            [
                new Token('"abc"', Token::TYPE_STRING, Token::FLAG_STRING_DOUBLE_QUOTES),
                ['value' => 'abc'],
                true,
            ],
            [
                new Token('"abc"', Token::TYPE_STRING, Token::FLAG_STRING_DOUBLE_QUOTES),
                ['value_str' => 'ABC'],
                true,
            ],
            [
                new Token('"abc"', Token::TYPE_STRING, Token::FLAG_STRING_DOUBLE_QUOTES),
                ['type' => Token::TYPE_STRING],
                true,
            ],
            [
                new Token('"abc"', Token::TYPE_STRING, Token::FLAG_STRING_DOUBLE_QUOTES),
                ['flags' => Token::FLAG_STRING_DOUBLE_QUOTES],
                true,
            ],

            [
                new Token('"abc"', Token::TYPE_STRING, Token::FLAG_STRING_DOUBLE_QUOTES),
                ['token' => '"abcd"'],
                false,
            ],
            [
                new Token('"abc"', Token::TYPE_STRING, Token::FLAG_STRING_DOUBLE_QUOTES),
                ['value' => 'abcd'],
                false,
            ],
            [
                new Token('"abc"', Token::TYPE_STRING, Token::FLAG_STRING_DOUBLE_QUOTES),
                ['value_str' => 'ABCd'],
                false,
            ],
            [
                new Token('"abc"', Token::TYPE_STRING, Token::FLAG_STRING_DOUBLE_QUOTES),
                ['type' => Token::TYPE_NUMBER],
                false,
            ],
            [
                new Token('"abc"', Token::TYPE_STRING, Token::FLAG_STRING_DOUBLE_QUOTES),
                ['flags' => Token::FLAG_STRING_SINGLE_QUOTES],
                false,
            ],
        ];
    }
}
