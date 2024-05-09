<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Utils;

use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Token;
use PhpMyAdmin\SqlParser\Utils\Tokens;

class TokensTest extends TestCase
{
    /**
     * @param array<string, string>[] $find
     * @param Token[]                 $replace
     *
     * @dataProvider replaceTokensProvider
     */
    public function testReplaceTokens(string $list, array $find, array $replace, string $expected): void
    {
        $this->assertEquals($expected, Tokens::replaceTokens($list, $find, $replace));
    }

    /**
     * @return array<int, array<int, string|array<string, string>[]|Token[]>>
     * @psalm-return list<array{string, list<array<string, string>>, Token[], string}>
     */
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
     * @param array<string, int|string> $pattern
     *
     * @dataProvider matchProvider
     */
    public function testMatch(Token $token, array $pattern, bool $expected): void
    {
        $this->assertSame($expected, Tokens::match($token, $pattern));
    }

    /**
     * @return array<int, array<int, Token|bool|array<string, int|string>>>
     * @psalm-return list<array{Token, array<string, (int|string)>, bool}>
     */
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
