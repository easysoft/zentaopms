<?php
/*
 * 这个脚本用来比较两个数据库表的结构差异，并生成相应的SQL语句来同步它们。
 * 从两个文件中读取表结构，比较差异，并输出同步SQL语句。
 * 比较的表结构文件格式为MySQL的CREATE TABLE语句。
 * 比较差异时遵循如下规则：
 * 1. 如果发现源表有而目标表没有的列，则不作处理。
 * 2. 如果发现目标表有而源表没有的列，则生成ADD COLUMN语句。
 * 3. 如果发现列定义不同，则生成ALTER COLUMN语句。
 * - 不考虑列顺序的不同。
 * - 如果数据类型不同、长度不同、是否允许NULL不同或默认值不同，则视为不同。
 * - 如果源表的字段取值范围更大，则视为数据类型和长度相同，仅比较是否允许NULL和默认值。生成ALTER COLUMN语句时，仅修改允许NULL和默认值。
 * - 如果源表字段是 ENUM，目标表不是 ENUM：
 *   - 若目标是 VARCHAR 且长度 >= 最长枚举值 → 视为兼容，仅比较 NULL/DEFAULT；
 *   - 否则 → 视为不兼容，生成 MODIFY 为 VARCHAR(N) 的语句；
 * - 忽略索引和约束的差异。
 * 使用方法：php diffTable.php source_db_file target_db_file [--debug]
 * 其中 source_db_file 和 target_db_file 是包含 CREATE TABLE 语句的文本文件。--debug 可选参数，用于输出调试信息。
 * 例如：
 * php diffTable.php db/standard/zentao21.7.7.sql db/standard/zentao21.7.8.sql --debug
 */

$debug = in_array('--debug', $argv);

function logDebug($msg)
{
    global $debug;
    if($debug)
    {
        echo "[DEBUG] " . $msg . "\n";
    }
}

function parseCreateTable($createTableSql)
{
    $tables = [];
    $sqls   = explode(";", $createTableSql);
    foreach($sqls as $sql)
    {
        $sql = trim($sql);
        if($sql === '') continue;
        if(stripos($sql, 'CREATE TABLE') === 0)
        {
            if(preg_match('/CREATE TABLE `([^`]+)` \((.*)\) ENGINE=InnoDB\s*/s', $sql, $matches))
            {
                $tableName  = $matches[1];
                $columnsDef = $matches[2];
                logDebug("Found table: $tableName");
                $columns    = parseColumns($columnsDef);
                $tables[$tableName] = $columns;
            }
            else
            {
                logDebug("Failed to parse CREATE TABLE: " . substr($sql, 0, 100) . "...");
            }
        }
    }
    logDebug("Parsed " . count($tables) . " tables from SQL");
    return $tables;
}

function parseColumns($columnsDef)
{
    $columns = [];
    $lines   = explode("\n", trim($columnsDef));
    foreach($lines as $line)
    {
        $line = trim($line, " ,");
        if(preg_match('/^`([^`]+)` (.+)$/', $line, $matches))
        {
            $columnName = $matches[1];
            $columnDef  = $matches[2];
            $columns[$columnName] = parseColumnDefinition($columnDef);
        }
    }
    return $columns;
}

function parseColumnDefinition($columnDef)
{
    $definition = [];
    // 解析 ENUM
    if(preg_match("/^ENUM\s*\((.+)\)/i", $columnDef, $enumMatches))
    {
        $definition['type']   = 'ENUM';
        $definition['length'] = null;
        preg_match_all("/'([^']*)'|\"([^\"]*)\"/", $enumMatches[1], $values);
        $definition['enumValues'] = array_unique(array_filter(array_merge($values[1], $values[2]), function($v)
        {
            return $v !== '';
        }));
        $definition['unsigned']   = false;
        logDebug("Parsed ENUM: " . json_encode($definition['enumValues']));
    }
    elseif(preg_match('/^([a-zA-Z]+)(\(([^)]+)\))?/', $columnDef, $matches))
    {
        $definition['type']       = strtoupper($matches[1]);
        $definition['length']     = isset($matches[3]) ? $matches[3] : null;
        $definition['enumValues'] = null;
        $definition['unsigned']   = stripos($columnDef, 'unsigned') !== false;
    }
    else
    {
        $definition['type']       = 'UNKNOWN';
        $definition['length']     = null;
        $definition['enumValues'] = null;
        $definition['unsigned']   = false;
        logDebug("Unknown column definition: $columnDef");
    }

    $definition['nullable'] = stripos($columnDef, 'NOT NULL') === false;

    if(preg_match('/DEFAULT\s+(NULL|\'[^\']*\'|"[^"]*"|[\w\.\-]+)/i', $columnDef, $matches))
    {
        $rawDefault = $matches[1];
        if(strtoupper(trim($rawDefault)) === 'NULL')
        {
            $definition['default'] = null;
        }
        else
        {
            $definition['default'] = trim($rawDefault, " '\"");
        }
    }
    else
    {
        $definition['default'] = null;
    }

    return $definition;
}

// 辅助：获取 ENUM 最长值长度
function getLongestEnumLength($enumValues)
{
    if(empty($enumValues)) return 1;
    return max(array_map('strlen', $enumValues));
}

function isIntegerType($type)
{
    $type = strtoupper($type);
    return in_array($type, ['TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'INTEGER', 'BIGINT']);
}

function isFloatType($type)
{
    $type = strtoupper($type);
    return in_array($type, ['FLOAT', 'DOUBLE', 'DECIMAL', 'NUMERIC']);
}

function isNumericType($type)
{
    $type = strtoupper($type);
    return in_array($type, [
        'TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'INTEGER',
        'BIGINT', 'FLOAT', 'DOUBLE', 'DECIMAL', 'NUMERIC'
    ]);
}

// 判断是否兼容（源 >= 目标）
function isCompatibleColumn($sourceDef, $targetDef)
{
    $sourceType = strtoupper($sourceDef['type']);
    $targetType = strtoupper($targetDef['type']);

    // 情况1：源是 ENUM
    if($sourceType === 'ENUM')
    {
        if($targetType === 'ENUM')
        {
            // 目标也是 ENUM：检查是否包含所有源值
            if(!$targetDef['enumValues']) return false;
            $missing = array_diff($sourceDef['enumValues'], $targetDef['enumValues']);
            if(!empty($missing))
            {
                logDebug("ENUM missing values in target: " . json_encode($missing));
                return false;
            }
            return true;
        }
        else
        {
            // 目标不是 ENUM
            if($targetType === 'VARCHAR')
            {
                $targetLen   = (int)($targetDef['length'] ?? 0);
                $requiredLen = getLongestEnumLength($sourceDef['enumValues']);
                $compatible  = $targetLen >= $requiredLen;
                logDebug("ENUM -> VARCHAR: required=$requiredLen, target=$targetLen, compatible=$compatible");
                return $compatible;
            }
            logDebug("ENUM -> non-VARCHAR ($targetType): not compatible");
            // 其他类型（如 TINYINT）视为不兼容
            return false;
        }
    }

    // 情况2：源不是 ENUM，走原兼容逻辑
    $numericHierarchy = [
        'TINYINT' => 1, 'SMALLINT' => 2, 'MEDIUMINT' => 3,
        'INT' => 4, 'INTEGER' => 4, 'BIGINT' => 5,
        'FLOAT' => 6, 'DOUBLE' => 7, 'DECIMAL' => 8
    ];
    $stringHierarchy = [
        'CHAR' => 1, 'VARCHAR' => 2, 'TINYTEXT' => 3,
        'TEXT' => 4, 'MEDIUMTEXT' => 5, 'LONGTEXT' => 6
    ];

    if(isset($numericHierarchy[$sourceType]) && isset($numericHierarchy[$targetType]))
    {
        if($numericHierarchy[$sourceType] > $numericHierarchy[$targetType]) return true;
        if($numericHierarchy[$sourceType] === $numericHierarchy[$targetType])
        {
            if(isIntegerType($sourceType))
            {
                return $sourceDef['unsigned'] === $targetDef['unsigned'];
            }
            if(isFloatType($sourceType))
            {
                $sourceLen     = explode(',', $sourceDef['length'] ?? '');
                $sourceInteger = (int)($sourceLen[0] ?? 0);
                $sourceDecimal = (int)($sourceLen[1] ?? 0);
                $targetLen     = explode(',', $targetDef['length'] ?? '');
                $targetInteger = (int)($targetLen[0] ?? 0);
                $targetDecimal = (int)($targetLen[1] ?? 0);
                return $sourceInteger >= $targetInteger && $sourceDecimal >= $targetDecimal;
            }
        }
    }

    if(in_array($sourceType, ['CHAR','VARCHAR']) && in_array($targetType, ['CHAR','VARCHAR']))
    {
        return (int)($sourceDef['length'] ?? 0) > (int)($targetDef['length'] ?? 0);
    }

    if(isset($stringHierarchy[$sourceType]) && isset($stringHierarchy[$targetType]))
    {
        return $stringHierarchy[$sourceType] >= $stringHierarchy[$targetType];
    }

    return false;
}

function compareColumnDefinitions($sourceDef, $targetDef)
{
    $sourceType = strtoupper($sourceDef['type']);
    $targetType = strtoupper($targetDef['type']);

    // 特殊处理 ENUM
    if($sourceType === 'ENUM')
    {
        if($sourceType !== $targetType)
        {
            logDebug("ENUM type mismatch: source=$sourceType, target=$targetType");
            return false; // 类型不同，直接不等
        }
        // 同为 ENUM，检查兼容性
        if(isCompatibleColumn($sourceDef, $targetDef))
        {
            $eq = ($sourceDef['nullable'] === $targetDef['nullable'] && $sourceDef['default'] === $targetDef['default']);
            logDebug("ENUM compatible, NULL/DEFAULT equal: " . ($eq ? 'YES' : 'NO'));
            return $eq;
        }
        return false; // 不兼容
    }

    // 非 ENUM：使用通用兼容逻辑
    if(isCompatibleColumn($sourceDef, $targetDef))
    {
        $eq = ($sourceDef['nullable'] === $targetDef['nullable'] && $sourceDef['default'] === $targetDef['default']);
        logDebug("Compatible type, NULL/DEFAULT equal: " . ($eq ? 'YES' : 'NO'));
        return $eq;
    }

    $strictEq = ($sourceDef['type'] === $targetDef['type'] &&
                 $sourceDef['unsigned'] === $targetDef['unsigned'] &&
                 $sourceDef['length'] === $targetDef['length'] &&
                 $sourceDef['nullable'] === $targetDef['nullable'] &&
                 $sourceDef['default'] === $targetDef['default']);
    logDebug("Strict compare: " . ($strictEq ? 'EQUAL' : 'DIFFERENT'));
    return $strictEq;
}

function buildColumnDefinition($columnDef)
{
    if($columnDef['type'] === 'ENUM' && !empty($columnDef['enumValues']))
    {
        $quoted = array_map(function($v)
        {
            return "'" . str_replace("'", "''", $v) . "'";
        }, $columnDef['enumValues']);
        $definition = "enum(" . implode(", ", $quoted) . ")"; // 小写 enum
    }
    else
    {
        $definition = strtolower($columnDef['type']); // 小写类型
        if($columnDef['length'])
        {
            $definition .= "({$columnDef['length']})";
        }
        if(isNumericType($columnDef['type']) && !empty($columnDef['unsigned']))
        {
            $definition .= " unsigned";
        }
    }
    $definition .= $columnDef['nullable'] ? " NULL" : " NOT NULL";

    if($columnDef['default'] === null)
    {
        if($columnDef['nullable'])
        {
            $definition .= " DEFAULT NULL";
        }
    }
    else
    {
        if(isNumericType($columnDef['type']))
        {
            $definition .= " DEFAULT " . $columnDef['default'];
        }
        else
        {
            $escaped = str_replace("'", "''", $columnDef['default']);
            $definition .= " DEFAULT '{$escaped}'";
        }
    }

    return $definition;
}

function buildColumnDefinitionForModify($sourceDef, $targetDef)
{
    // 如果源是 ENUM 且目标不兼容，生成 VARCHAR(N)
    if(strtoupper($sourceDef['type']) === 'ENUM')
    {
        $requiredLen = getLongestEnumLength($sourceDef['enumValues']);
        if($requiredLen < $targetDef['length'])
        {
            $requiredLen = $targetDef['length'];
        }
        $definition = "varchar({$requiredLen})";
    }
    else
    {
        // 否则保留源类型和长度
        $definition = strtolower($sourceDef['type']);
        if($sourceDef['length'])
        {
            $definition .= "({$sourceDef['length']})";
        }
        if(isNumericType($sourceDef['type']) && !empty($sourceDef['unsigned']))
        {
            $definition .= " unsigned";
        }
    }

    $definition .= $targetDef['nullable'] ? " NULL" : " NOT NULL";

    if($targetDef['default'] === null)
    {
        if($targetDef['nullable'])
        {
            $definition .= " DEFAULT NULL";
        }
    }
    else
    {
        if(isNumericType($targetDef['type']))
        {
            $definition .= " DEFAULT " . $targetDef['default'];
        }
        else
        {
            $escaped = str_replace("'", "''", $targetDef['default']);
            $definition .= " DEFAULT '{$escaped}'";
        }
    }

    return $definition;
}

function compareTables($sourceTables, $targetTables)
{
    $sqlStatements = [];
    logDebug("Comparing " . count($targetTables) . " target tables");
    foreach($targetTables as $tableName => $targetColumns)
    {
        logDebug("Processing table: $tableName");
        if(!isset($sourceTables[$tableName]))
        {
            logDebug("Source table $tableName not found, skipping");
            continue;
        }
        $alterStatements = [];
        $enumStatements  = [];
        $sourceColumns   = $sourceTables[$tableName];
        foreach($targetColumns as $columnName => $targetDef)
        {
            if(!isset($sourceColumns[$columnName]))
            {
                logDebug("Column $columnName exists in target but not source → ADD");
                $sqlStatements[] = "ALTER TABLE `$tableName` ADD COLUMN `$columnName` " . buildColumnDefinition($targetDef) . ($columnName === 'id' ? ' AUTO_INCREMENT PRIMARY KEY' : '') . ";";
            }
            else
            {
                $enum2int  = false;
                $sourceDef = $sourceColumns[$columnName];
                if(!compareColumnDefinitions($sourceDef, $targetDef))
                {
                    // 判断是否只需修改 NULL/DEFAULT，或需要改类型
                    if(isCompatibleColumn($sourceDef, $targetDef))
                    {
                        // 兼容，只改 NULL/DEFAULT
                        $colDef = buildColumnDefinitionForModify($sourceDef, $targetDef);
                        logDebug("Compatible difference → MODIFY (only NULL/DEFAULT): $colDef");
                    }
                    else
                    {
                        // 不兼容：若源是 ENUM，目标非 ENUM 且不满足长度，转为 VARCHAR
                        if(strtoupper($sourceDef['type']) === 'ENUM')
                        {
                            $requiredLen = getLongestEnumLength($sourceDef['enumValues']);
                            if($requiredLen < $targetDef['length'])
                            {
                                $requiredLen = $targetDef['length'];
                            }
                            // 构造新的 VARCHAR 定义（使用目标的 nullable/default）
                            $colDef = "varchar({$requiredLen})" . ($targetDef['nullable'] ? " NULL" : " NOT NULL");
                            if($targetDef['default'] !== null)
                            {
                                $escaped = str_replace("'", "''", $targetDef['default']);
                                $colDef .= " DEFAULT '{$escaped}'";
                            }
                            logDebug("ENUM incompatible → MODIFY to VARCHAR($requiredLen): $colDef");
                            $enum2int = true;
                            foreach($sourceDef['enumValues'] as $val)
                            {
                                if(!is_numeric($val))
                                {
                                    $enum2int = false;
                                    break;
                                }
                            }
                        }
                        else
                        {
                            // 其他情况：直接使用目标定义
                            $colDef = buildColumnDefinition($targetDef);
                            logDebug("Incompatible → MODIFY to target definition: $colDef");
                        }
                    }
                    $alterStatements[] = "MODIFY COLUMN `$columnName` {$colDef}" . ($columnName === 'id' ? ' AUTO_INCREMENT' : '');
                    if($enum2int)
                    {
                        logDebug("Incompatible → MODIFY to INT for ENUM to INT conversion");
                        $enumStatements[] = "MODIFY COLUMN `$columnName` tinyint unsigned" . ($targetDef['nullable'] ? " NULL" : " NOT NULL") . ($targetDef['default'] !== null ? " DEFAULT " . $targetDef['default'] : '');
                    }
                }
                else
                {
                    logDebug("Column $columnName identical");
                }
            }
        }
        if($alterStatements)
        {
            $sqlStatements[] = "ALTER TABLE `$tableName`\n  " . implode(",\n  ", $alterStatements) . ";";
        }
        if($enumStatements)
        {
            $sqlStatements[] = "ALTER TABLE `$tableName`\n  " . implode(",\n  ", $enumStatements) . ";";
        }
    }
    logDebug("Generated " . count($sqlStatements) . " SQL statements");
    return $sqlStatements;
}

if($argc !== 3 && $argc !== 4)
{
    echo "[Info] Usage: php diffTable.php source_db_file target_db_file [--debug]\n";
    echo "[Info] Example: php diffTable.php db/standard/zentao21.7.7.sql db/standard/zentao21.7.8.sql --debug\n";
    echo "[Info] The source_db_file and target_db_file should contain CREATE TABLE statements.\n";
    echo "[Info] The --debug option is optional for debug output.\n";
    echo "[Info] Note: The CREATE TABLE statements must end with ENGINE=InnoDB;\n";
    exit(1);
}

$sourceDbFile = $argv[1];
$targetDbFile = $argv[2];

if(!file_exists($sourceDbFile))
{
    echo "[Info] Error: Source file '$sourceDbFile' does not exist.\n";
    exit(1);
}
if(!file_exists($targetDbFile))
{
    echo "[Info] Error: Target file '$targetDbFile' does not exist.\n";
    exit(1);
}

logDebug("Reading source file: $sourceDbFile");
logDebug("Reading target file: $targetDbFile");

$sourceSql = file_get_contents($sourceDbFile);
$targetSql = file_get_contents($targetDbFile);

if(trim($sourceSql) === '')
{
    echo "[Info] Warning: Source file is empty.\n";
}
if(trim($targetSql) === '')
{
    echo "[Info] Warning: Target file is empty.\n";
}

$sourceTables = parseCreateTable($sourceSql);
$targetTables = parseCreateTable($targetSql);

if(empty($targetTables))
{
    echo "[Info] No target tables parsed. Check CREATE TABLE syntax (must end with ENGINE=InnoDB;).\n";
    exit(1);
}

$sqlStatements = compareTables($sourceTables, $targetTables);
foreach($sqlStatements as $key => $sql)
{
    $sqlStatements[$key] = str_replace(" varchar(1) ", " char(1) ", $sql); // 优化：长度为1的VARCHAR改为CHAR
}

file_put_contents('difftable.sql', implode("\n", $sqlStatements) . "\n");
echo "[Info] Generated SQL statements saved to difftable.sql\n";
