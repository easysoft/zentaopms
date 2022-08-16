<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Lexer;

use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Token;

class TokenTest extends TestCase
{
    public function testExtractKeyword(): void
    {
        $tok = new Token('SelecT', Token::TYPE_KEYWORD, Token::FLAG_KEYWORD_RESERVED);
        $this->assertEquals($tok->value, 'SELECT');

        $tok = new Token('aS', Token::TYPE_KEYWORD, Token::FLAG_KEYWORD_RESERVED);
        $this->assertEquals($tok->value, 'AS');
    }

    public function testExtractWhitespace(): void
    {
        $tok = new Token(" \t \r \n ", Token::TYPE_WHITESPACE);
        $this->assertEquals($tok->value, ' ');
    }

    public function testExtractBool(): void
    {
        $tok = new Token('false', Token::TYPE_BOOL);
        $this->assertFalse($tok->value);

        $tok = new Token('True', Token::TYPE_BOOL);
        $this->assertTrue($tok->value);
    }

    public function testExtractNumber(): void
    {
        $tok = new Token('--42', Token::TYPE_NUMBER, Token::FLAG_NUMBER_NEGATIVE);
        $this->assertEquals($tok->value, 42);

        $tok = new Token('---42', Token::TYPE_NUMBER, Token::FLAG_NUMBER_NEGATIVE);
        $this->assertEquals($tok->value, -42);

        $tok = new Token('0xFE', Token::TYPE_NUMBER, Token::FLAG_NUMBER_HEX);
        $this->assertEquals($tok->value, 0xFE);

        $tok = new Token('-0xEF', Token::TYPE_NUMBER, Token::FLAG_NUMBER_NEGATIVE | Token::FLAG_NUMBER_HEX);
        $this->assertEquals($tok->value, -0xEF);

        $tok = new Token('3.14', Token::TYPE_NUMBER, Token::FLAG_NUMBER_FLOAT);
        $this->assertEquals($tok->value, 3.14);
    }

    public function testExtractString(): void
    {
        $tok = new Token('"foo bar "', Token::TYPE_STRING);
        $this->assertEquals($tok->value, 'foo bar ');

        $tok = new Token("' bar foo '", Token::TYPE_STRING);
        $this->assertEquals($tok->value, ' bar foo ');

        $tok = new Token("'\''", Token::TYPE_STRING);
        $this->assertEquals($tok->value, '\'');

        $tok = new Token('"\c\d\e\f\g\h\i\j\k\l\m\p\q\s\u\v\w\x\y\z"', Token::TYPE_STRING);
        $this->assertEquals($tok->value, 'cdefghijklmpqsuvwxyz');
    }

    public function testExtractSymbol(): void
    {
        $tok = new Token('@foo', Token::TYPE_SYMBOL, Token::FLAG_SYMBOL_VARIABLE);
        $this->assertEquals($tok->value, 'foo');

        $tok = new Token('`foo`', Token::TYPE_SYMBOL, Token::FLAG_SYMBOL_BACKTICK);
        $this->assertEquals($tok->value, 'foo');

        $tok = new Token('@`foo`', Token::TYPE_SYMBOL, Token::FLAG_SYMBOL_VARIABLE);
        $this->assertEquals($tok->value, 'foo');

        $tok = new Token(':foo', Token::TYPE_SYMBOL, Token::FLAG_SYMBOL_PARAMETER);
        $this->assertEquals($tok->value, 'foo');

        $tok = new Token('?', Token::TYPE_SYMBOL, Token::FLAG_SYMBOL_PARAMETER);
        $this->assertEquals($tok->value, '?');
    }

    public function testInlineToken(): void
    {
        $token = new Token(" \r \n \t ");
        $this->assertEquals($token->getInlineToken(), ' \r \n \t ');
    }
}
