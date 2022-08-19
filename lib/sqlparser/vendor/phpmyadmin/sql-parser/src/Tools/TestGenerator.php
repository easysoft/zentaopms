<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tools;

use Exception;
use PhpMyAdmin\SqlParser\Context;
use PhpMyAdmin\SqlParser\Exceptions\LexerException;
use PhpMyAdmin\SqlParser\Exceptions\ParserException;
use PhpMyAdmin\SqlParser\Lexer;
use PhpMyAdmin\SqlParser\Parser;
use Zumba\JsonSerializer\JsonSerializer;

use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function in_array;
use function is_dir;
use function json_decode;
use function json_encode;
use function mkdir;
use function print_r;
use function scandir;
use function sprintf;
use function strpos;
use function substr;

use const JSON_PRESERVE_ZERO_FRACTION;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_UNICODE;

/**
 * Used for test generation.
 */
class TestGenerator
{
    /**
     * Generates a test's data.
     *
     * @param string $query the query to be analyzed
     * @param string $type  test's type (may be `lexer` or `parser`)
     *
     * @return array
     */
    public static function generate($query, $type = 'parser')
    {
        /**
         * Lexer used for tokenizing the query.
         *
         * @var Lexer
         */
        $lexer = new Lexer($query);

        /**
         * Parsed used for analyzing the query.
         * A new instance of parser is generated only if the test requires.
         *
         * @var Parser
         */
        $parser = $type === 'parser' ? new Parser($lexer->list) : null;

        /**
         * Lexer's errors.
         *
         * @var array
         */
        $lexerErrors = [];

        /**
         * Parser's errors.
         *
         * @var array
         */
        $parserErrors = [];

        // Both the lexer and the parser construct exception for errors.
        // Usually, exceptions contain a full stack trace and other details that
        // are not required.
        // The code below extracts only the relevant information.

        // Extracting lexer's errors.
        if (! empty($lexer->errors)) {
            /** @var LexerException $err */
            foreach ($lexer->errors as $err) {
                $lexerErrors[] = [
                    $err->getMessage(),
                    $err->ch,
                    $err->pos,
                    $err->getCode(),
                ];
            }

            $lexer->errors = [];
        }

        // Extracting parser's errors.
        if (! empty($parser->errors)) {
            /** @var ParserException $err */
            foreach ($parser->errors as $err) {
                $parserErrors[] = [
                    $err->getMessage(),
                    $err->token,
                    $err->getCode(),
                ];
            }

            $parser->errors = [];
        }

        return [
            'query' => $query,
            'lexer' => $lexer,
            'parser' => $parser,
            'errors' => [
                'lexer' => $lexerErrors,
                'parser' => $parserErrors,
            ],
        ];
    }

    /**
     * Builds a test.
     *
     * Reads the input file, generates the data and writes it back.
     *
     * @param string $type   the type of this test
     * @param string $input  the input file
     * @param string $output the output file
     * @param string $debug  the debug file
     * @param bool   $ansi   activate quotes ANSI mode
     */
    public static function build($type, $input, $output, $debug = null, $ansi = false)
    {
        // Support query types: `lexer` / `parser`.
        if (! in_array($type, ['lexer', 'parser'])) {
            throw new Exception('Unknown test type (expected `lexer` or `parser`).');
        }

        /**
         * The query that is used to generate the test.
         *
         * @var string
         */
        $query = file_get_contents($input);

        // There is no point in generating a test without a query.
        if (empty($query)) {
            throw new Exception('No input query specified.');
        }

        if ($ansi === true) {
            // set ANSI_QUOTES for ansi tests
            Context::setMode('ANSI_QUOTES');
        }

        $mariaDbPos = strpos($input, '_mariadb_');
        if ($mariaDbPos !== false) {// Keep in sync with TestCase.php
            // set context
            $mariaDbVersion = (int) substr($input, $mariaDbPos + 9, 6);
            Context::load('MariaDb' . $mariaDbVersion);
        }

        $test = static::generate($query, $type);

        // unset mode, reset to default every time, to be sure
        Context::setMode();
        $serializer = new JsonSerializer();
        // Writing test's data.
        $encoded = $serializer->serialize($test);
        $encoded = json_encode(
            json_decode($encoded),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION
        );
        file_put_contents($output, $encoded);

        // Dumping test's data in human readable format too (if required).
        if (empty($debug)) {
            return;
        }

        file_put_contents($debug, print_r($test, true));
    }

    /**
     * Generates recursively all tests preserving the directory structure.
     *
     * @param string     $input  the input directory
     * @param string     $output the output directory
     * @param mixed|null $debug
     */
    public static function buildAll($input, $output, $debug = null)
    {
        $files = scandir($input);

        foreach ($files as $file) {
            // Skipping current and parent directories.
            if (($file === '.') || ($file === '..')) {
                continue;
            }

            // Appending the filename to directories.
            $inputFile = $input . '/' . $file;
            $outputFile = $output . '/' . $file;
            $debugFile = $debug !== null ? $debug . '/' . $file : null;

            if (is_dir($inputFile)) {
                // Creating required directories to maintain the structure.
                // Ignoring errors if the folder structure exists already.
                if (! is_dir($outputFile)) {
                    mkdir($outputFile);
                }

                if (($debug !== null) && (! is_dir($debugFile))) {
                    mkdir($debugFile);
                }

                // Generating tests recursively.
                static::buildAll($inputFile, $outputFile, $debugFile);
            } elseif (substr($inputFile, -3) === '.in') {
                // Generating file names by replacing `.in` with `.out` and
                // `.debug`.
                $outputFile = substr($outputFile, 0, -3) . '.out';
                if ($debug !== null) {
                    $debugFile = substr($debugFile, 0, -3) . '.debug';
                }

                // Building the test.
                if (! file_exists($outputFile)) {
                    sprintf("Building test for %s...\n", $inputFile);
                    static::build(
                        strpos($inputFile, 'lex') !== false ? 'lexer' : 'parser',
                        $inputFile,
                        $outputFile,
                        $debugFile,
                        strpos($inputFile, 'ansi') !== false
                    );
                } else {
                    sprintf("Test for %s already built!\n", $inputFile);
                }
            }
        }
    }
}
