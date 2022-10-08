<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Components\WithKeyword;
use PhpMyAdmin\SqlParser\Lexer;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use stdClass;

class WithStatementTest extends TestCase
{
    public function testWith(): void
    {
        $sql = <<<SQL
WITH categories(identifier, name, parent_id) AS (
    SELECT c.identifier, c.name, c.parent_id FROM category c WHERE c.identifier = 'a'
    UNION ALL
    SELECT c.identifier, c.name, c.parent_id FROM categories, category c WHERE c.identifier = categories.parent_id
), foo AS ( SELECT * FROM test )
SELECT * FROM categories
SQL;

        $lexer = new Lexer($sql);

        $lexerErrors = $this->getErrorsAsArray($lexer);
        $this->assertCount(0, $lexerErrors);
        $parser = new Parser($lexer->list);
        $parserErrors = $this->getErrorsAsArray($parser);
        $this->assertCount(0, $parserErrors);
        $this->assertCount(2, $parser->statements);

        // phpcs:disable Generic.Files.LineLength.TooLong
        $expected = <<<SQL
WITH categories(identifier, name, parent_id) AS (SELECT c.identifier, c.name, c.parent_id FROM category AS `c` WHERE c.identifier = 'a' UNION ALL SELECT c.identifier, c.name, c.parent_id FROM categories, category AS `c` WHERE c.identifier = categories.parent_id), foo AS (SELECT * FROM test)
SQL;
        // phpcs:enable
        $this->assertEquals($expected, $parser->statements[0]->build());
        $this->assertEquals('SELECT * FROM categories', $parser->statements[1]->build());
    }

    public function testWithHasErrors(): void
    {
        $sql = <<<SQL
WITH categories(identifier, name, parent_id) AS (
    SOMETHING * FROM foo
)
SELECT * FROM categories
SQL;

        $lexer = new Lexer($sql);

        $lexerErrors = $this->getErrorsAsArray($lexer);
        $this->assertCount(0, $lexerErrors);
        $parser = new Parser($lexer->list);
        $parserErrors = $this->getErrorsAsArray($parser);
        $this->assertCount(2, $parserErrors);
    }

    public function testWithEmbedParenthesis(): void
    {
        $sql = <<<SQL
WITH categories AS (
    SELECT * FROM (SELECT * FROM foo)
)
SELECT * FROM categories
SQL;

        $lexer = new Lexer($sql);
        $lexerErrors = $this->getErrorsAsArray($lexer);
        $this->assertCount(0, $lexerErrors);
        $parser = new Parser($lexer->list);
        $parserErrors = $this->getErrorsAsArray($parser);
        $this->assertCount(0, $parserErrors);

        // phpcs:disable Generic.Files.LineLength.TooLong
        $expected = <<<SQL
WITH categories AS (SELECT * FROM (SELECT * FROM foo))
SQL;
        // phpcs:enable
        $this->assertEquals($expected, $parser->statements[0]->build());
    }

    public function testWithHasUnclosedParenthesis(): void
    {
        $sql = <<<SQL
WITH categories(identifier, name, parent_id) AS (
    SELECT * FROM (SELECT * FROM foo
)
SELECT * FROM categories
SQL;

        $lexer = new Lexer($sql);

        $lexerErrors = $this->getErrorsAsArray($lexer);
        $this->assertCount(0, $lexerErrors);
        $parser = new Parser($lexer->list);
        $parserErrors = $this->getErrorsAsArray($parser);
        $this->assertEquals($parserErrors[0][0], 'A closing bracket was expected.');
    }

    public function testBuildWrongWithKeyword(): void
    {
        $this->expectExceptionMessage('Can not build a component that is not a WithKeyword');
        WithKeyword::build(new stdClass());
    }

    public function testBuildBadWithKeyword(): void
    {
        $this->expectExceptionMessage('No statement inside WITH');
        WithKeyword::build(new WithKeyword('test'));
    }
}
