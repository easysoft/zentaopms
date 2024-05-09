<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Utils;

use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Utils\BufferedQuery;

use function count;
use function str_split;

class BufferedQueryTest extends TestCase
{
    /**
     * @param array<string, bool> $options
     * @param string[]            $expected
     * @psalm-param array{delimiter?: non-empty-string, parse_delimiter?: bool, add_delimiter?: bool} $options
     * @psalm-param positive-int $chunkSize
     *
     * @dataProvider extractProvider
     */
    public function testExtract(
        string $query,
        int $chunkSize,
        array $options,
        array $expected
    ): void {
        $chunks = str_split($query, $chunkSize);
        $count = count($chunks);

        /**
         * The array of extracted statements.
         */
        $statements = [];

        /**
         * The `BufferedQuery` instance used for extraction.
         */
        $bq = new BufferedQuery('', $options);

        // Feeding chunks and extracting queries.
        $i = 0;
        while ($i < $count) {
            $stmt = $bq->extract();

            if ($stmt) {
                $statements[] = $stmt;
            } else {
                $bq->query .= $chunks[$i++];
            }
        }

        // Feeding ended, extracting remaining queries.
        while ($stmt = $bq->extract(true)) {
            $statements[] = $stmt;
        }

        $this->assertEquals($expected, $statements);
    }

    /**
     * @return array<int, array<int, int|string|string[]|bool[]>>
     * @psalm-return list<array{string, positive-int, array{parse_delimiter: bool, add_delimiter: bool}, string[]}>
     */
    public function extractProvider(): array
    {
        $query =
            '/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;' . "\n" .
            '/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;' . "\n" .
            '/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;' . "\n" .
            '/*!40101 SET NAMES utf8mb4 */;' . "\n" .
            '' . "\n" .
            'SET SQL_MODE = \'NO_AUTO_VALUE_ON_ZERO\';' . "\n" .
            'SET time_zone = "+00:00";' . "\n" .
            '' . "\n" .
            '/* a comment */ DELIMITER $$' . "\n" .
            '' . "\n" .
            '# Bash-like comment sytanx.' . "\n" .
            'CREATE DEFINER=`root`@`localhost` PROCEDURE `film_in_stock` (IN `p_film_id` ' .
            'INT, IN `p_store_id` INT, OUT `p_film_count` INT)  READS SQL DATA' . "\n" .
            'BEGIN' . "\n" .
            '     SELECT inventory_id' . "\n" .
            '     FROM inventory' . "\n" .
            '     WHERE film_id = p_film_id' . "\n" .
            '     AND store_id = p_store_id' . "\n" .
            '     AND inventory_in_stock(inventory_id);' . "\n" .
            '' . "\n" .
            '     SELECT FOUND_ROWS() INTO p_film_count;' . "\n" .
            'END$$' . "\n" .
            '' . "\n" .
            'DELIMITER ;' . "\n" .
            '' . "\n" .
            '-- --------------------------------------------------------' . "\n" .
            '' . "\n" .
            '--' . "\n" .
            '-- Table structure for `actor`' . "\n" .
            '--' . "\n" .
            '' . "\n" .
            '/* C-like comment syntax. */' . "\n" .
            'CREATE TABLE IF NOT EXISTS `actor` (' . "\n" .
            '`actor_id` SMALLINT(5) UNSIGNED NOT NULL,' . "\n" .
            '`first_name` VARCHAR(45) NOT NULL,' . "\n" .
            '`last_name` VARCHAR(45) NOT NULL,' . "\n" .
            '`last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP' . "\n" .
            ') ENGINE=InnoDB DEFAULT CHARSET=utf8;' . "\n" .
            '' . "\n" .
            '/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;' . "\n" .
            '/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;' . "\n" .
            '/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */';

        return [
            [
                "SELECT '\'';\nSELECT '\'';",
                8,
                [
                    'parse_delimiter' => true,
                    'add_delimiter' => true,
                ],
                [
                    "SELECT '\'';",
                    "SELECT '\'';",
                ],
            ],

            [
                'SELECT \\',
                8,
                [
                    'parse_delimiter' => false,
                    'add_delimiter' => false,
                ],
                ['SELECT \\'],
            ],

            [
                "CREATE TABLE `test` (\n" .
                "  `txt` varchar(10)\n" .
                ");\n" .
                "INSERT INTO `test` (`txt`) VALUES('abc');\n" .
                "INSERT INTO `test` (`txt`) VALUES('\\\\');\n" .
                "INSERT INTO `test` (`txt`) VALUES('xyz');\n",
                8,
                [
                    'parse_delimiter' => true,
                    'add_delimiter' => true,
                ],
                [
                    "CREATE TABLE `test` (\n" .
                    "  `txt` varchar(10)\n" .
                    ');',
                    "INSERT INTO `test` (`txt`) VALUES('abc');",
                    "INSERT INTO `test` (`txt`) VALUES('\\\\');",
                    "INSERT INTO `test` (`txt`) VALUES('xyz');",
                ],
            ],

            [
                'SELECT """""""";' .
                'SELECT """\\\\"""',
                8,
                [
                    'parse_delimiter' => true,
                    'add_delimiter' => true,
                ],
                [
                    'SELECT """""""";',
                    'SELECT """\\\\"""',
                ],
            ],

            [
                'DELIMITER A_VERY_LONG_DEL' . "\n" .
                'SELECT 1 A_VERY_LONG_DEL' . "\n" .
                'DELIMITER ;',
                3,
                [
                    'parse_delimiter' => true,
                    'add_delimiter' => true,
                ],
                [
                    'DELIMITER A_VERY_LONG_DEL',
                    'SELECT 1 A_VERY_LONG_DEL',
                    'DELIMITER ;',
                ],
            ],

            [
                $query,
                32,
                [
                    'parse_delimiter' => false,
                    'add_delimiter' => false,
                ],
                [
                    '/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */',

                    '/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */',

                    '/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */',

                    '/*!40101 SET NAMES utf8mb4 */',

                    'SET SQL_MODE = \'NO_AUTO_VALUE_ON_ZERO\'',

                    'SET time_zone = "+00:00"',

                    '# Bash-like comment sytanx.' . "\n" .
                    'CREATE DEFINER=`root`@`localhost` PROCEDURE `film_in_stock` (IN `p_film_id` ' .
                    'INT, IN `p_store_id` INT, OUT `p_film_count` INT)  READS SQL DATA' . "\n" .
                    'BEGIN' . "\n" .
                    '     SELECT inventory_id' . "\n" .
                    '     FROM inventory' . "\n" .
                    '     WHERE film_id = p_film_id' . "\n" .
                    '     AND store_id = p_store_id' . "\n" .
                    '     AND inventory_in_stock(inventory_id);' . "\n" .
                    '' . "\n" .
                    '     SELECT FOUND_ROWS() INTO p_film_count;' . "\n" .
                    'END',

                    '-- --------------------------------------------------------' . "\n" .
                    '' . "\n" .
                    '--' . "\n" .
                    '-- Table structure for `actor`' . "\n" .
                    '--' . "\n" .
                    '' . "\n" .
                    '/* C-like comment syntax. */' . "\n" .
                    'CREATE TABLE IF NOT EXISTS `actor` (' . "\n" .
                    '`actor_id` SMALLINT(5) UNSIGNED NOT NULL,' . "\n" .
                    '`first_name` VARCHAR(45) NOT NULL,' . "\n" .
                    '`last_name` VARCHAR(45) NOT NULL,' . "\n" .
                    '`last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP' . "\n" .
                    ') ENGINE=InnoDB DEFAULT CHARSET=utf8',

                    '/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */',

                    '/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */',

                    '/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */',
                ],
            ],

            [
                $query,
                32,
                [
                    'parse_delimiter' => true,
                    'add_delimiter' => false,
                ],
                [
                    '/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */',

                    '/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */',

                    '/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */',

                    '/*!40101 SET NAMES utf8mb4 */',

                    'SET SQL_MODE = \'NO_AUTO_VALUE_ON_ZERO\'',

                    'SET time_zone = "+00:00"',

                    '/* a comment */  DELIMITER $$',

                    '# Bash-like comment sytanx.' . "\n" .
                    'CREATE DEFINER=`root`@`localhost` PROCEDURE `film_in_stock` (IN `p_film_id` ' .
                    'INT, IN `p_store_id` INT, OUT `p_film_count` INT)  READS SQL DATA' . "\n" .
                    'BEGIN' . "\n" .
                    '     SELECT inventory_id' . "\n" .
                    '     FROM inventory' . "\n" .
                    '     WHERE film_id = p_film_id' . "\n" .
                    '     AND store_id = p_store_id' . "\n" .
                    '     AND inventory_in_stock(inventory_id);' . "\n" .
                    '' . "\n" .
                    '     SELECT FOUND_ROWS() INTO p_film_count;' . "\n" .
                    'END',

                    'DELIMITER ;',

                    '-- --------------------------------------------------------' . "\n" .
                    '' . "\n" .
                    '--' . "\n" .
                    '-- Table structure for `actor`' . "\n" .
                    '--' . "\n" .
                    '' . "\n" .
                    '/* C-like comment syntax. */' . "\n" .
                    'CREATE TABLE IF NOT EXISTS `actor` (' . "\n" .
                    '`actor_id` SMALLINT(5) UNSIGNED NOT NULL,' . "\n" .
                    '`first_name` VARCHAR(45) NOT NULL,' . "\n" .
                    '`last_name` VARCHAR(45) NOT NULL,' . "\n" .
                    '`last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP' . "\n" .
                    ') ENGINE=InnoDB DEFAULT CHARSET=utf8',

                    '/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */',

                    '/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */',

                    '/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */',
                ],
            ],

            [
                $query,
                64,
                [
                    'parse_delimiter' => false,
                    'add_delimiter' => true,
                ],
                [
                    '/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;',

                    '/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;',

                    '/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;',

                    '/*!40101 SET NAMES utf8mb4 */;',

                    'SET SQL_MODE = \'NO_AUTO_VALUE_ON_ZERO\';',

                    'SET time_zone = "+00:00";',

                    '# Bash-like comment sytanx.' . "\n" .
                    'CREATE DEFINER=`root`@`localhost` PROCEDURE `film_in_stock` (IN `p_film_id` ' .
                    'INT, IN `p_store_id` INT, OUT `p_film_count` INT)  READS SQL DATA' . "\n" .
                    'BEGIN' . "\n" .
                    '     SELECT inventory_id' . "\n" .
                    '     FROM inventory' . "\n" .
                    '     WHERE film_id = p_film_id' . "\n" .
                    '     AND store_id = p_store_id' . "\n" .
                    '     AND inventory_in_stock(inventory_id);' . "\n" .
                    '' . "\n" .
                    '     SELECT FOUND_ROWS() INTO p_film_count;' . "\n" .
                    'END$$',

                    '-- --------------------------------------------------------' . "\n" .
                    '' . "\n" .
                    '--' . "\n" .
                    '-- Table structure for `actor`' . "\n" .
                    '--' . "\n" .
                    '' . "\n" .
                    '/* C-like comment syntax. */' . "\n" .
                    'CREATE TABLE IF NOT EXISTS `actor` (' . "\n" .
                    '`actor_id` SMALLINT(5) UNSIGNED NOT NULL,' . "\n" .
                    '`first_name` VARCHAR(45) NOT NULL,' . "\n" .
                    '`last_name` VARCHAR(45) NOT NULL,' . "\n" .
                    '`last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP' . "\n" .
                    ') ENGINE=InnoDB DEFAULT CHARSET=utf8;',

                    '/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;',

                    '/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;',

                    '/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */',
                ],
            ],
        ];
    }
}
