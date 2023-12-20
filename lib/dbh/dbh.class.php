<?php
/**
 * ZenTaoPHP的dbh类。
 * The dbh class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * DBH类。
 * DBH, database handler.
 *
 * @package lib
 */
class dbh
{
    /**
     * Flag for database.
     *
     * @var string MASTER|SLAVE|BI
     * @access private
     */
    private $flag;

    /**
     * PDO.
     *
     * @var object
     * @access private
     */
    private $pdo;

    /**
     * Database config.
     *
     * @var object
     * @access private
     */
    private $config;

    /**
     * Constructor
     *
     * @param object $config
     * @param bool   $setSchema
     * @param string $flag
     * @access public
     * @return void
     */
    public function __construct($config, $setSchema = true, $flag = 'MASTER')
    {
        $dsn = "{$config->driver}:host={$config->host};port={$config->port}";
        if($setSchema) $dsn .= ";dbname={$config->name}";

        $pdo = new PDO($dsn, $config->user, $config->password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if($config->driver == 'mysql')
        {
            $pdo->exec("SET NAMES {$config->encoding}");
            if(isset($this->config->strictMode) and $this->config->strictMode == false) $pdo->exec("SET @@sql_mode= ''");
        }
        else if($setSchema)
        {
            $pdo->exec("SET SCHEMA {$config->name}");
        }

        $this->pdo    = $pdo;
        $this->config = $config;
        $this->flag   = $flag;
    }

    /**
     * Execute sql.
     *
     * @param string $sql
     * @access public
     * @return PDOStatement|false
     */
    public function exec($sql)
    {
        $sql = $this->formatSQL($sql);
        if(!$sql) return true;

        if(class_exists('dao')) dao::$querys[] = "[$this->flag] " . dao::processKeywords($sql);
        return $this->pdo->exec($sql);
    }

    /**
     * Query sql.
     *
     * @param string $sql
     * @access public
     * @return PDOStatement|false
     */
    public function query($sql)
    {
        $sql = $this->formatSQL($sql);

        if(class_exists('dao')) dao::$querys[] = "[$this->flag] " . dao::processKeywords($sql);

        $result = $this->pdo->query($sql);
        return $result;
    }

    /**
     * Query raw sql.
     *
     * @param string $sql
     * @access public
     * @return PDOStatement|false
     */
    public function rawQuery($sql)
    {
        if(class_exists('dao')) dao::$querys[] = "[$this->flag] " . dao::processKeywords($sql);

        $result = $this->pdo->query($sql);

        return $result;
    }

    /**
     * Set attribute.
     *
     * @param int $attribute
     * @param mixed $value
     * @access public
     * @return bool
     */
    public function setAttribute($attribute, $value)
    {
        return $this->pdo->setAttribute($attribute, $value);
    }

    /**
     * Check db exits or not.
     *
     * @access public
     * @return bool
     */
    public function dbExists()
    {
        switch($this->config->driver)
        {
            case 'mysql':
                $sql = "SHOW DATABASES like '{$this->config->name}'";
                break;
            case 'dm':
                $sql = "SELECT * FROM ALL_OBJECTS WHERE object_type='SCH' AND owner='{$this->config->name}'";
                break;
            default:
                $sql = '';
        }
        return $this->rawQuery($sql)->fetch();
    }

    /**
     * Check table exits or not.
     *
     * @param  string    $tableName
     * @access public
     * @return void
     */
    public function tableExits($tableName)
    {
        $tableName = str_replace('`', "'", $tableName);
        $tableName = str_replace("'", "", $tableName);
        $sql = "SHOW TABLES FROM {$this->config->name} like '{$tableName}'";
        switch($this->config->driver)
        {
            case 'mysql':
                $sql = "SHOW TABLES FROM {$this->config->name} like '{$tableName}'";
                break;
            case 'dm':
                $sql = "SELECT * FROM all_tables WHERE owner='{$this->config->name}' AND table_name='{$tableName}'";
                break;
            default:
                $sql = '';
        }

        return $this->rawQuery($sql)->fetch();
    }

    /**
     * Create database.
     *
     * @param  string    $version
     * @access public
     * @return PDOStatement|false
     */
    public function createDB($version)
    {
        switch($this->config->driver)
        {
            case 'mysql':
                $sql = "CREATE DATABASE `{$this->config->name}`";
                if($version > 4.1) $sql .= " DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
                return $this->rawQuery($sql);

            case 'dm':
                /*
                $tableSpace = strtoupper($this->config->name);
                $res = $this->rawQuery("SELECT * FROM dba_data_files WHERE TABLESPACE_NAME = '$tableSpace'")->fetchAll();

                if(empty($res))
                {
                    $createTableSpace = "CREATE TABLESPACE $tableSpace DATAFILE '{$this->config->name}.DBF' size 150 AUTOEXTEND ON";
                    $createUser       = "CREATE USER {$this->config->name} IDENTIFIED by {$this->config->password} DEFAULT TABLESPACE {$this->config->name} DEFAULT INDEX TABLESPACE {$this->config->name}";

                    $this->rawQuery($createTableSpace);
                    $this->rawQuery($createUser);
                }
                 */

                $createSchema = "CREATE SCHEMA {$this->config->name} AUTHORIZATION {$this->config->user}";
                return $this->rawQuery($createSchema);

            default:
                return false;
        }
    }

    /**
     * Use database or schema.
     *
     * @param string $dbName
     * @access public
     * @return PDOStatement|false
     */
    public function useDB($dbName)
    {
        switch($this->config->driver)
        {
            case 'mysql':
                return $this->exec("USE {$this->config->name}");

            case 'dm':
                return $this->exec("SET SCHEMA {$this->config->name}");

            default:
                return false;
        }
    }

    /**
     * Format sql.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function formatSQL($sql)
    {
        switch($this->config->driver)
        {
            case 'dm':
                return $this->formatDmSQL($sql);

            default:
                return $sql;
        }
    }

    /**
     * Format dm sql.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function formatDmSQL($sql)
    {
        $sql = trim($sql);
        $sql = $this->formatFunction($sql);

        if(defined('IN_UPGRADE'))
        {
            $sql = $this->processDmChangeColumn($sql);
            $sql = $this->processDmTableIndex($sql);
        }

        $actionPos = strpos($sql, ' ');
        $action    = strtoupper(substr($sql, 0, $actionPos));
        $setPos    = 0;
        switch($action)
        {
            case 'SELECT':
                return $this->formatField($sql);
            case 'REPLACE':
                $result = $this->processReplace($sql);
                if($result != $sql) return $result;

                $sql    = str_replace('REPLACE', 'INSERT', $sql);
                $action = 'INSERT';
            case 'INSERT':
            case 'UPDATE':
                $setPos = stripos($sql, ' VALUES');
                $sql    = str_replace('0000-00-00', '1970-01-01', $sql);
                $sql    = str_replace('00:00:00', '00:00:01', $sql);
                if(strpos($sql, "\\'") !== FALSE) $sql = str_replace("\\'", "''''", $sql);
                if(strpos($sql, '\"') !== FALSE) $sql = str_replace('\"', '"', $sql);
                if(strpos($sql, '\\\\') !== FALSE) $sql = str_replace('\\\\', '\\', $sql);
                break;
            case 'CREATE':
                if(stripos($sql, 'CREATE VIEW') === 0) $sql = str_replace('CREATE VIEW', 'CREATE OR REPLACE VIEW', $sql);
                if(stripos($sql, 'CREATE FUNCTION') === 0) return '';

                if(stripos($sql, 'CREATE OR REPLACE VIEW ') === 0)
                {
                    // Modify if function.
                    $fieldsBegin = stripos($sql, 'select');
                    $fieldsEnd   = stripos($sql, 'from');
                    $fields      = substr($sql, $fieldsBegin+6, $fieldsEnd-$fieldsBegin-6);
                    $fieldList   = preg_split("/,(?![^(]+\))/", $fields);
                    foreach($fieldList as $key => $field)
                    {
                        $aliasPos = stripos($field, ' AS ');
                        $subField = substr($field, 0, $aliasPos);
                        if(stripos($field, 'SUM(') === 0) $subField = substr($subField, 4, -1);

                        $fieldParts = preg_split("/\+(?![^(]+\))/", $subField);
                        foreach($fieldParts as $pkey => $fieldPart)
                        {
                            $originField = trim($fieldPart);
                            if(stripos($originField, 'if(') === false) continue;
                            $fieldParts[$pkey] = $this->formatDmIfFunction($originField);
                        }
                        $fieldList[$key] = str_replace($subField, implode(' + ', $fieldParts), $field);
                    }
                    $fields = implode(',', $fieldList);
                    return substr($sql, 0, $fieldsBegin+6) . $fields . substr($sql, $fieldsEnd);
                }
                elseif(stripos($sql, 'CREATE UNIQUE INDEX') === 0 || stripos($sql, 'CREATE INDEX') === 0)
                {
                    preg_match('/ON\s+[^.`\s]+\.`([^\s`]+)`/', $sql, $matches);

                    $tableName = str_replace($this->config->prefix, '', $matches);
                    $sql       = preg_replace('/INDEX\ +\`/', 'INDEX `' . strtolower($tableName[1]) . '_', $sql);
                }
            case 'ALTER':
                $sql = $this->formatField($sql);
                $sql = $this->formatAttr($sql);
                return $sql;
            case 'SET':
                if(stripos($sql, 'SET SCHEMA') === 0) return $sql;
            case 'USE':
                return '';
            case 'DESC';
                $tableName = str_replace('DESC ', '', $sql);
                return "select COLUMN_NAME as Field from all_tab_columns where Table_Name='$tableName'";
            case 'DROP':
                return $this->formatField($sql);
        }

        if($setPos <= 0) return $sql;

        $fields = substr($sql, 0, $setPos);
        $fields = $this->formatField($fields);

        $sql = $fields . substr($sql, $setPos);

        /* DMDB must set IDENTITY_INSERT 'on' to insert id field. */
        if($action == 'INSERT' and stripos($fields, '"id"') !== FALSE)
        {
            $tableBegin = strpos($sql, '"' . $this->config->prefix);
            $tableEnd   = strpos($sql, '"', $tableBegin + 1);
            $tableName  = '' . $this->config->name . '."' . substr($sql, $tableBegin + 1, $tableEnd - $tableBegin - 1) . '"';
            return "SET IDENTITY_INSERT $tableName ON;" . $sql;
        }

        return $sql;
    }

    /**
     * Format field.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function formatField($sql)
    {
        switch($this->config->driver)
        {
            case 'dm':
                $sql = str_replace('`', '"', $sql);
                return $sql;

            default:
                return $sql;
        }
    }

    /**
     * Format dm table index.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function processDmTableIndex($sql)
    {
        if(strpos($sql, 'DROP INDEX') === FALSE) return $sql;
        return preg_replace('/DROP INDEX `(\w+)` ON `zt_(\w+)`/', 'DROP INDEX IF EXISTS `$2_$1`', $sql);
    }

    /**
     * Format dm change column.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function processDmChangeColumn($sql)
    {
        if(strpos($sql, 'CHANGE COLUMN') === FALSE) return $sql;
        return preg_replace('/ALTER TABLE `([^`]+)` CHANGE COLUMN `([^`]+)` `([^`]+)` (\w+)/', 'ALTER TABLE `$1` RENAME COLUMN `$2` TO `$3`;', $sql);
    }

    /**
     * Format function.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function formatFunction($sql)
    {
        switch($this->config->driver)
        {
            case 'dm':
                /* DATE convert to TO_CHAR. */
                $sql = preg_replace("/\bDATE\(([^)]*)\)/",  "TO_CHAR($1, 'yyyy-mm-dd')", $sql, -1);

                return $sql;

            default:
                return $sql;
        }
    }

    /**
     * Format if function of dmdb.
     *
     * @param  string $field
     * @access private
     * @return string
     */
    public function formatDmIfFunction($field)
    {
        preg_match('/if\(.+\)+/i', $field, $matches);

        $if = $matches[0];
        if(substr_count($if, '(') == 1)
        {
            $pos = strpos($if, ')');
            $if  = substr($if, 0, $pos+1);
        }

        /* fix sum(if(..., 1, 0)) , count(if(..., 1, 0)) */
        if(substr($if, strlen($if)-2) == '))' and (stripos($field, 'sum(') == 0 or stripos($field, 'count(') == 0)) $if = substr($if, 0, strlen($if)-1);

        $parts = explode(',', substr($if, 3, strlen($if)-4)); // remove 'if(' and ')'
        $case  = 'CASE WHEN ' . implode(',', array_slice($parts, 0, count($parts)-2)) . ' THEN ' . $parts[count($parts)-2] . ' ELSE ' . $parts[count($parts)-1] . ' END';
        $field = str_ireplace($if, $case, $field);

        return $field;
    }

    /**
     * Format attribute of field.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function formatAttr($sql)
    {
        switch($this->config->driver)
        {
            case 'dm':
                $pos = stripos($sql, ' ENGINE');
                if($pos > 0) $sql = substr($sql, 0, $pos);

                $sql = preg_replace('/\(\ *\d+\ *\)/', '', $sql);

                $replace = array(
                    " AUTO_INCREMENT"           => ' IDENTITY(1, 1)',
                    " int "                     => ' integer ',
                    " mediumint "               => ' integer ',
                    " smallint "                => ' integer ',
                    " tinyint "                 => ' integer ',
                    " varchar "                 => ' varchar(255) ',
                    " char "                    => ' varchar(255) ',
                    " mediumtext "              => ' text ',
                    " mediumtext,"              => ' text,',
                    " longtext "                => ' text ',
                    "COLLATE 'utf8_general_ci'" => ' ',
                    " unsigned "                => ' ',
                    " zerofill "                => ' ',
                    "0000-00-00"                => '1970-01-01',
                );

                $sql = preg_replace('/ enum[\_0-9a-z\,\'\"\( ]+\)+/i', ' varchar(255) ', $sql);
                $sql = str_ireplace(array_keys($replace), array_values($replace), $sql);
                $sql = preg_replace('/\,\s+key[\_\"0-9a-z ]+\(+[\,\_\"0-9a-z ]+\)+/i', '', $sql);
                $sql = preg_replace('/\,\s*(unique|fulltext)*\s+key[\_\"0-9a-z ]+\(+[\,\_\"0-9a-z ]+\)+/i', '', $sql);
                $sql = preg_replace('/ float\s*\(+[\,\_\"0-9a-z ]+\)+/i', ' float', $sql);

                /* Convert "date" datetime to "date" datetime(0) to fix bug 25725, dm database datetime default 6 */
                preg_match_all('/"[0-9a-zA-Z]+" datetime/', $sql, $datetimeMatch);
                if(!empty($datetimeMatch))
                {
                    foreach($datetimeMatch[0] as $match) $sql = str_replace($match, $match . '(0)', $sql);
                }

                if(strpos($sql, "ALTER TABLE") === 0)
                {
                    $sql = $this->convertAlterTableSql($sql);
                    if(stripos($sql, "ADD") !== false)
                    {
                        // 使用正则表达式匹配并去除 "AFTER" 关键字及其后面的内容
                        $pattern = "/\s+AFTER\s+.+$/i";
                        $sql     = preg_replace($pattern, "", $sql);
                    }
                }
        }

        return $sql;
    }

    /**
     * Process replace into sql.
     *
     * @param mixed $sql
     * @access public
     * @return void
     */
    public function processReplace($sql)
    {
        // 解析REPLACE INTO语句，提取出表名、字段和值
        $matches = [];
        preg_match('/^REPLACE\s+INTO\s+`?([\w_]+)`?\s*\((.*)\)\s+VALUES\s*\(([^()]+)\)\s*$/i', $sql, $matches);
        if(empty($matches)) return $sql;
        $table_name = $matches[1];
        $columns    = array_map('trim', explode(',', $matches[2]));
        $values     = array_map('trim', explode(', ', $matches[3]));
        if($table_name == '' or $columns == '' or $values == '') return $sql;

        // 构造SELECT语句，查询数据是否存在
        $where = [];
        foreach ($columns as $index => $column) {
            $value  = trim($values[$index], "'");
            $column = trim($column, '`');
            $values[$index]  = $value;
            $columns[$index] = $column;
            $where[] = "`$column` = '$value'";
        }

        $select_sql = "SELECT * FROM `$table_name` WHERE " . implode(' AND ', $where);

        $result = $this->query($select_sql);
        $result = $result->fetchAll();
        $sql    = in_array('id', $columns) ? "SET IDENTITY_INSERT `$table_name` ON;" : '';

        if($result)
        {
            // 数据已存在，构造UPDATE语句并执行
            $set   = [];
            $where = [];
            foreach ($columns as $index => $column) {
                $value   = $values[$index];
                $set[]   = "`$column` = '$value'";
                $where[] = "`$column` = '$value'";
            }

            $sql .= "UPDATE `$table_name` SET " . implode(', ', $set) . " WHERE " . implode(' AND ', $where);
        }
        else
        {
            // 数据不存在，构造INSERT INTO语句并执行
            $selectColumn = array();
            $selectValue  = array();
            foreach ($columns as $index => $column) {
                $selectColumn[] .= "`$column`";
                $selectValue[]  .= "'{$values[$index]}'";
            }
            $sql .= "INSERT INTO `$table_name` (" . implode(', ', $selectColumn) . ") VALUES (" . implode(', ', $selectValue) . ")";
        }

        return $sql;
    }

    /**
     * Convert alter table sql.
     *
     * @param mixed $sql
     * @access public
     * @return void
     */
    public function convertAlterTableSql($sql)
    {
        /* If table has datas and sql no default values defined, add default ''/0. */
        if(strpos($sql, "NOT NULL") !== false && strpos($sql, "DEFAULT") === false)
        {
            $default = '';
            if(strpos($sql, "integer") !== false) $default = 0;
            $sql = str_replace("NOT NULL", "NOT NULL DEFAULT '" . $default ."'", $sql);
        }

        $pattern = '/ALTER TABLE "(.*?)" CHANGE "(.*?)" "(.*?)" (.*?)(?:;|$)/';
        preg_match($pattern, $sql, $matches);
        if(count($matches) != 5) return $sql;

        $tableName     = $matches[1];
        $oldColumnName = $matches[2];
        $newColumnName = $matches[3];
        $params        = str_replace("'", "''", $matches[4]);

        $sql  = 'begin ';
        if($oldColumnName != $newColumnName) $sql .= "execute immediate 'ALTER TABLE $tableName ALTER " . '"' . $oldColumnName . '" RENAME TO "' . $newColumnName . '"' . "';";
        $sql .= "execute immediate 'ALTER TABLE $tableName MODIFY " . '"' . $newColumnName . '" ' . $params . "';";
        $sql .= 'end;';

        return $sql;
    }

    /**
     * Quote.
     *
     * @param string $string
     * @param int    $parameter_type
     * @access public
     * @return string
     */
    public function quote($string, $parameter_type = PDO::PARAM_STR)
    {
        return $this->pdo->quote($string, $parameter_type);
    }

    /**
     * Get last insert id.
     *
     * @param string $name
     * @access public
     * @return string|false
     */
    public function lastInsertId($name = null)
    {
        return $this->pdo->lastInsertId($name);
    }

    /**
     * Begin transaction.
     *
     * @access public
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Check in transaction or not.
     *
     * @access public
     * @return bool
     */
    public function inTransaction()
    {
        return $this->pdo->inTransaction();
    }

    /**
     * Roll back if transaction failed.
     *
     * @access public
     * @return bool
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    /**
     * Commit transaction.
     *
     * @access public
     * @return bool
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Prepares a statement for execution and returns a statement object.
     *
     * @access public
     * @return bool
     */
    public function prepare($query, $options = array())
    {
        return $this->pdo->prepare($query, $options);
    }
}
