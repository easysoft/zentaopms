<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\CreateDefinition;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class CreateDefinitionTest extends TestCase
{
    public function testParse()
    {
        $component = CreateDefinition::parse(
            new Parser(),
            $this->getTokensList('(str TEXT, FULLTEXT INDEX indx (str))')
        );
        $this->assertEquals('str', $component[0]->name);
        $this->assertEquals('FULLTEXT INDEX', $component[1]->key->type);
        $this->assertEquals('indx', $component[1]->key->name);
        $this->assertEquals('FULLTEXT INDEX `indx` (`str`)', (string) $component[1]);
    }

    public function testParse2()
    {
        $component = CreateDefinition::parse(
            new Parser(),
            $this->getTokensList('(str TEXT NOT NULL INVISIBLE)')
        );
        $this->assertEquals('str', $component[0]->name);
        $this->assertEquals('TEXT', $component[0]->type->name);
        $this->assertTrue($component[0]->options->has('INVISIBLE'));
        $this->assertTrue($component[0]->options->has('NOT NULL'));
    }

    public function testParseErr1()
    {
        $parser = new Parser();
        $component = CreateDefinition::parse(
            $parser,
            $this->getTokensList('(str TEXT, FULLTEXT INDEX indx (str)')
        );
        $this->assertCount(2, $component);

        $this->assertEquals(
            'A closing bracket was expected.',
            $parser->errors[0]->getMessage()
        );
    }

    public function testParseErr2()
    {
        $parser = new Parser();
        CreateDefinition::parse(
            $parser,
            $this->getTokensList(')')
        );

        $this->assertEquals(
            'An opening bracket was expected.',
            $parser->errors[0]->getMessage()
        );
    }

    public function testBuild()
    {
        $parser = new Parser(
            'CREATE TABLE `payment` (' .
            '-- snippet' . "\n" .
            '`customer_id` smallint(5) unsigned NOT NULL,' .
            'CONSTRAINT `fk_payment_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON UPDATE CASCADE' .
            ') ENGINE=InnoDB"'
        );
        $this->assertEquals(
            'CONSTRAINT `fk_payment_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON UPDATE CASCADE',
            CreateDefinition::build($parser->statements[0]->fields[1])
        );
    }

    public function testBuild2()
    {
        $parser = new Parser(
            'CREATE TABLE `payment` (' .
            '-- snippet' . "\n" .
            '`customer_id` smallint(5) unsigned NOT NULL,' .
            '`customer_data` longtext CHARACTER SET utf8mb4 CHARSET utf8mb4_bin NOT NULL CHECK (json_valid(customer_data)),' .
            'CONSTRAINT `fk_payment_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON UPDATE CASCADE' .
            ') ENGINE=InnoDB"'
        );
        $this->assertEquals(
            'CONSTRAINT `fk_payment_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON UPDATE CASCADE',
            CreateDefinition::build($parser->statements[0]->fields[2])
        );
    }

    public function testBuild3()
    {
        $parser = new Parser(
            'DROP TABLE IF EXISTS `searches`;'
            . 'CREATE TABLE `searches` ('
            . '  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,'
            . '  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,'
            . '  `public_name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,'
            . '  `group_id` smallint(5) unsigned NOT NULL DEFAULT \'0\','
            . '  `shortdesc` tinytext COLLATE utf8_unicode_ci,'
            . '  `show_separators` tinyint(1) NOT NULL DEFAULT \'0\','
            . '  `show_separators_two` tinyint(1) NOT NULL DEFAULT FALSE,'
            . '  `deleted` tinyint(1) NOT NULL DEFAULT \'0\','
            . '  PRIMARY KEY (`id`)'
            . ') ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;'
            . ''
            . 'ALTER TABLE `searches` ADD `admins_only` BOOLEAN NOT NULL DEFAULT FALSE AFTER `show_separators`;'
        );
        $this->assertEquals(
            '`public_name` varchar(120) COLLATE utf8_unicode_ci NOT NULL',
            CreateDefinition::build($parser->statements[1]->fields[2])
        );
        $this->assertEquals(
            '`show_separators` tinyint(1) NOT NULL DEFAULT \'0\'',
            CreateDefinition::build($parser->statements[1]->fields[5])
        );
        $this->assertEquals(
            '`show_separators_two` tinyint(1) NOT NULL DEFAULT FALSE',
            CreateDefinition::build($parser->statements[1]->fields[6])
        );
    }

    public function testBuildWithInvisibleKeyword()
    {
        $parser = new Parser(
            'CREATE TABLE `payment` (' .
            '-- snippet' . "\n" .
            '`customer_id` smallint(5) unsigned NOT NULL INVISIBLE,' .
            '`customer_data` longtext CHARACTER SET utf8mb4 CHARSET utf8mb4_bin NOT NULL ' .
            'CHECK (json_valid(customer_data)),CONSTRAINT `fk_payment_customer` FOREIGN KEY ' .
            '(`customer_id`) REFERENCES `customer` (`customer_id`) ON UPDATE CASCADE' .
            ') ENGINE=InnoDB"'
        );
        // TODO: when not supporting PHP 5.3 anymore, replace this by CreateStatement::class.
        $this->assertInstanceOf('PhpMyAdmin\\SqlParser\\Statements\\CreateStatement', $parser->statements[0]);
        $this->assertEquals(
            '`customer_id` smallint(5) UNSIGNED NOT NULL INVISIBLE',
            CreateDefinition::build($parser->statements[0]->fields[0])
        );
    }
}
