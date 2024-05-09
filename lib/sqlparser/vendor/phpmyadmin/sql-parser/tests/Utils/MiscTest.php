<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Utils;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Utils\Misc;

class MiscTest extends TestCase
{
    /**
     * @param mixed[] $expected
     * @psalm-param array<string, array{
     *   alias: (string|null),
     *   tables: array<string, array{alias: (string|null), columns: array<string, string>}>
     * }> $expected
     *
     * @dataProvider getAliasesProvider
     */
    public function testGetAliases(string $query, ?string $db, array $expected): void
    {
        $parser = new Parser($query);
        $statement = empty($parser->statements[0]) ?
            null : $parser->statements[0];
        $this->assertEquals($expected, Misc::getAliases($statement, $db));
    }

    /**
     * @return array<int, array<int, string|mixed[]|null>>
     * @psalm-return list<array{string, (string|null), array<string, array{
     *   alias: (string|null),
     *   tables: array<string, array{alias: (string|null), columns: array<string, string>}>
     * }>}>
     */
    public function getAliasesProvider(): array
    {
        return [
            [
                'select * from (select 1) tbl',
                'mydb',
                [],
            ],
            [
                'select i.name as `n`,abcdef gh from qwerty i',
                'mydb',
                [
                    'mydb' => [
                        'alias' => null,
                        'tables' => [
                            'qwerty' => [
                                'alias' => 'i',
                                'columns' => [
                                    'name' => 'n',
                                    'abcdef' => 'gh',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'select film_id id,title from film',
                'sakila',
                [
                    'sakila' => [
                        'alias' => null,
                        'tables' => [
                            'film' => [
                                'alias' => null,
                                'columns' => ['film_id' => 'id'],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'select `sakila`.`A`.`actor_id` as aid,`F`.`film_id` `fid`,'
                . 'last_update updated from `sakila`.actor A join `film_actor` as '
                . '`F` on F.actor_id = A.`actor_id`',
                'sakila',
                [
                    'sakila' => [
                        'alias' => null,
                        'tables' => [
                            'film_actor' => [
                                'alias' => 'F',
                                'columns' => [
                                    'film_id' => 'fid',
                                    'last_update' => 'updated',
                                ],
                            ],
                            'actor' => [
                                'alias' => 'A',
                                'columns' => [
                                    'actor_id' => 'aid',
                                    'last_update' => 'updated',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'SELECT film_id FROM (SELECT * FROM film) as f;',
                'sakila',
                [],
            ],
            [
                '',
                null,
                [],
            ],
            [
                'SELECT 1',
                null,
                [],
            ],
            [
                'SELECT * FROM orders AS ord WHERE 1',
                'db',
                [
                    'db' => [
                        'alias' => null,
                        'tables' => [
                            'orders' => [
                                'alias' => 'ord',
                                'columns' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
