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
     * PDO Statement.
     *
     * @var object
     * @access private
     */
    private $statement;

    /**
     * 应用配置信息。
     * The app config.
     *
     * @var object
     * @access private
     */
    private $config;

    /**
     * Database dbConfig.
     *
     * @var object
     * @access private
     */
    private $dbConfig;

    /**
     * The SQL string of last query.
     *
     * @var string
     * @access private
     */
    private $sql;

    /**
     * 主库还是从库的标记。
     * Flag for master or slave.
     *
     * @var array
     * @access public
     */
    public static $flags = [];

    /**
     * 触发SQL执行的文件和行号。
     * The file and line number that triggered the SQL execution.
     *
     * @var array
     * @access public
     */
    public static $traces = [];

    /**
     * 执行的请求，所有的查询都保存在该数组。
     * The queries executed. Every query will be saved in this array.
     *
     * @var array
     * @access public
     */
    public static $queries = [];

    /**
     * SQL执行时长。
     * The duration of SQL execution.
     *
     * @var array
     * @access public
     */
    public static $durations = [];

    /**
     * 数据库的标识符引号。
     * identifier quote character.
     *
     * @var string
     * @access public
    */
    public $iqchar = '`';

    /**
     * Constructor
     *
     * @param  object $dbConfig
     * @param  bool   $setSchema
     * @param  string $flag
     * @access public
     * @return void
     */
    public function __construct($dbConfig, $setSchema = true, $flag = 'MASTER')
    {
        global $config;

        $this->config   = $config;
        $this->dbConfig = $dbConfig;
        $this->flag     = $flag;
        $this->pdo      = $this->pdoInit($setSchema);

        $queries = [];
        if(in_array($dbConfig->driver, $config->mysqlDriverList))
        {
            if($dbConfig->driver == 'mysql') $queries[] = "SET NAMES {$dbConfig->encoding}" . ($dbConfig->collation ? " COLLATE '{$dbConfig->collation}'" : '');
            if(isset($dbConfig->strictMode) && empty($dbConfig->strictMode)) $queries[] = "SET @@sql_mode= ''";
        }
        else
        {
            $this->iqchar = '"';

            if($setSchema)
            {
                if($dbConfig->driver == 'dm')
                {
                    $queries[] = "SET SCHEMA {$dbConfig->name}";
                }
                elseif(in_array($dbConfig->driver, $config->pgsqlDriverList))
                {
                    $schema = $dbConfig->schema ?? 'public';
                    $queries[] = "SET SCHEMA '{$schema}'";
                }
            }
        }
        if(!empty($queries))
        {
            foreach($queries as $query) $this->rawQuery($query);
        }
    }

    /**
     * 获取PDO驱动名称。
     * Get pdo driver name.
     *
     * @access private
     * @return string
     */
    private function getPdoDriver()
    {
        if($this->dbConfig->driver == 'kingbase') return 'kdb';
        if(in_array($this->dbConfig->driver, $this->config->mysqlDriverList)) return 'mysql';
        if(in_array($this->dbConfig->driver, $this->config->pgsqlDriverList)) return 'pgsql';
        return $this->dbConfig->driver;
    }

    /**
     * 初始化PDO对象。
     * Init pdo.
     *
     * @param  bool   $setSchema
     * @access private
     * @return object
     */
    private function pdoInit($setSchema)
    {
        $driver = $this->getPdoDriver();
        $dsn    = "{$driver}:host={$this->dbConfig->host};port={$this->dbConfig->port}";

        if($setSchema)
        {
            $dsn .= ";dbname={$this->dbConfig->name}";
        }
        elseif(in_array($this->dbConfig->driver, $this->config->pgsqlDriverList))
        {
            $dsn .= ";dbname={$this->dbConfig->driver}"; // default database
        }

        $password = helper::decryptPassword($this->dbConfig->password);
        $pdo      = new PDO($dsn, $this->dbConfig->user, $password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    /**
     * Process PDO/SQL error.
     *
     * @param  object $exception
     * @access public
     * @return void
     */
    public function sqlError(object $exception)
    {
        $newException = new PDOException($exception->getMessage() . " ,the sql is: '{$this->sql}'");
        $newException->errorInfo = $exception->errorInfo;
        throw $newException;
    }

    /**
     * Execute sql.
     *
     * @param  string $sql
     * @access public
     * @return int|false
     */
    public function exec($sql)
    {
        return $this->executeSql($sql, __FUNCTION__);
    }

    /**
     * Query sql.
     *
     * @param  string $sql
     * @see    https://www.php.net/manual/en/pdo.query.php
     * @access public
     * @return PDOStatement|false
     */
    public function query($sql)
    {
        return $this->executeSql($sql, __FUNCTION__);
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
        return $this->executeSql($sql, __FUNCTION__);
    }

    /**
     * 执行SQL语句并记录执行时间和调用栈信息。
     * Execute SQL and record the duration and trace info.
     *
     * @param  string $sql
     * @param  string $mode exec|query|rawQuery
     * @access private
     * @return mixed
     */
    private function executeSql($sql, $mode)
    {
        if($mode == 'exec' || $mode == 'query')
        {
            $sql = $this->formatSQL($sql);
            if(!$sql) return false;

            if($mode == 'exec' && !empty($this->dbConfig->enableSqlite)) $this->pushSqliteQueue($sql);
        }

        $result = false;
        $begin  = microtime(true);
        $method = $mode == 'exec' ? 'exec' : 'query';

        try
        {
            $result = $this->pdo->$method($sql);
        }
        catch(PDOException $e)
        {
            $this->sqlError($e);
        }
        finally
        {
            dbh::$flags[]     = $this->flag;
            dbh::$queries[]   = dao::processKeywords($sql);
            dbh::$durations[] = round(microtime(true) - $begin, 6);

            if(!empty($this->config->debug))
            {
                $trace = $this->getTrace();
                if($trace) dbh::$traces[] = 'vim +' . $trace['line'] . ' ' . $trace['file'];
            }
        }
        return $result;
    }

    /**
     * 获取当前执行的 SQL 的调用栈信息。
     * Get the call stack information of the currently executed SQL.
     *
     * @access private
     * @return array
     */
    private function getTrace()
    {
        $traces = array_reverse(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
        foreach($traces as $key => $trace)
        {
            $class    = $trace['class'] ?? '';
            $function = $trace['function'] ?? '';
            if($class == 'settingModel' && strpos(',getItem,getItems,setItem,setItems,updateItem,deleteItems,', ",$function,") !== false) return $trace;
            if($class == 'baseDAO' && strpos(',exec,fetch,fetchPairs,fetchGroup,explain,showTables,getTableEngines,descTable,', ",$function,") !== false) return $trace;
            if($class == 'baseDAO' && $function == 'getProfiles') return $traces[$key + 1];
            if($class == 'baseDAO' && $function == 'fetchAll')
            {
                if($traces[$key + 1]['class'] == 'baseDAO' && $traces[$key + 1]['function'] == 'extractSQLFields') return $traces[$key + 2];
                return $trace;
            }
            if($class == 'baseDAO' && stripos($function, 'findBy') === 0) return $trace;
            if($class == 'baseRouter' && $function == 'dbQuery') return $trace;
            if($class == 'dbh' && strpos('exec,query,rawQuery', $function) !== false) return $trace;
        }
        return [];
    }

    /**
     * Prepare a PDO statement.
     *
     * @param  string $sql
     * @access public
     * @return PDOStatement|false
     */
    public function prepare($sql)
    {
        $this->sql = $sql;

        try
        {
            $this->statement = $this->pdo->prepare($sql);
        }
        catch(PDOException $e)
        {
            $this->sqlError($e);
        }

        return $this->statement;
    }

    /**
     * Prepare and execute a PDO statement.
     *
     * @param  string $sql
     * @param  array  $params
     * @access public
     * @return PDOStatement|false
     */
    public function execute($sql, $params)
    {
        $this->statement = $this->prepare($sql);

        try
        {
            $this->statement->execute($params);
        }
        catch(PDOException $e)
        {
            $this->sqlError($e);
        }

        return $this->statement;
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
        if($this->dbConfig->driver == 'dm')
        {
            $sql = "SELECT * FROM ALL_OBJECTS WHERE object_type='SCH' AND owner='{$this->dbConfig->name}'";
            return $this->rawQuery($sql)->fetch();
        }

        if(in_array($this->dbConfig->driver, $this->config->mysqlDriverList))
        {
            $sql = "SHOW DATABASES like '{$this->dbConfig->name}'";
            return $this->rawQuery($sql)->fetch();
        }

        if(in_array($this->dbConfig->driver, $this->config->pgsqlDriverList))
        {
            $sql = "SELECT * FROM pg_database WHERE datname ='{$this->dbConfig->name}'";
            return $this->rawQuery($sql)->fetch();
        }

        return false;
    }

    /**
     * Check table exist or not.
     *
     * @param  string    $tableName
     * @access public
     * @return void
     */
    public function tableExist($tableName)
    {
        $tableName = str_replace(array("'", '`'), "", $tableName);

        if($this->dbConfig->driver == 'dm')
        {
            $sql = "SELECT * FROM all_tables WHERE owner='{$this->dbConfig->name}' AND table_name='{$tableName}'";
            return $this->rawQuery($sql)->fetch();
        }

        if(in_array($this->dbConfig->driver, $this->config->mysqlDriverList))
        {
            $sql = "SHOW TABLES FROM {$this->dbConfig->name} like '{$tableName}'";
            return $this->rawQuery($sql)->fetch();
        }

        if(in_array($this->dbConfig->driver, $this->config->pgsqlDriverList))
        {
            $this->useDB($this->dbConfig->name);
            $sql = "SELECT * FROM information_schema.tables WHERE table_catalog = '{$this->dbConfig->name}' AND table_name='{$tableName}'";
            return $this->rawQuery($sql)->fetch();
        }

        return false;
    }

    /**
     * 获取数据库支持的字符集和排序规则。
     * Get the database charset and collation.
     *
     * @param  string $database
     * @access public
     * @return array
     */
    public function getDatabaseCharsetAndCollation(string $database = ''): array
    {
        if($this->dbConfig->driver != 'mysql') return ['charset' => 'utf8', 'collation' => ''];

        if(empty($database)) $database = $this->dbConfig->name;

        $sql    = "SELECT DEFAULT_CHARACTER_SET_NAME AS charset, DEFAULT_COLLATION_NAME AS collation FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '{$database}';";
        $result = $this->rawQuery($sql)->fetch();
        if($result) return (array)$result;

        return $this->getServerCharsetAndCollation();
    }

    /**
     * 获取服务器支持的字符集和排序规则。
     * Get the server charset and collation.
     *
     * @access public
     * @return array
     */
    public function getServerCharsetAndCollation(): array
    {
        if($this->dbConfig->driver != 'mysql') return ['charset' => 'utf8', 'collation' => ''];

        $charsets  = [];
        $statement = $this->rawQuery("SHOW CHARSET WHERE Charset LIKE 'utf8%';");
        while($charset = $statement->fetch(PDO::FETCH_ASSOC)) $charsets[$charset['Charset']] = ['charset' => $charset['Charset'], 'collation' => $charset['Default collation']];

        return $charsets['utf8mb4'] ?? $charsets['utf8mb3'] ?? ['charset' => 'utf8', 'collation' => 'utf8_general_ci'];
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
        if($this->dbConfig->driver == 'dm')
        {
            $createSchema = "CREATE SCHEMA {$this->dbConfig->name} AUTHORIZATION {$this->dbConfig->user}";
            return $this->rawQuery($createSchema);
        }

        if($this->dbConfig->driver == 'mysql')
        {
            $result = $this->getServerCharsetAndCollation();
            $sql    = "CREATE DATABASE `{$this->dbConfig->name}` DEFAULT CHARACTER SET {$result['charset']} COLLATE {$result['collation']}";
            return $this->rawQuery($sql);
        }

        if($this->dbConfig->driver == 'oceanbase' || in_array($this->dbConfig->driver, $this->config->pgsqlDriverList))
        {
            $sql = "CREATE DATABASE {$this->dbConfig->name}";
            return $this->rawQuery($sql);
        }

        return false;
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
        if($this->dbConfig->driver == 'dm') return $this->exec("SET SCHEMA {$dbName}");

        if(in_array($this->dbConfig->driver, $this->config->mysqlDriverList)) return $this->exec("USE {$dbName}");

        if(in_array($this->dbConfig->driver, $this->config->pgsqlDriverList))
        {
            $this->pdo = $this->pdoInit(true);
            $schema = $this->dbConfig->schema ?? 'public';
            return $this->exec("SET SCHEMA '{$schema}'");
        }

        return false;
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
        $this->sql = $sql;

        if($this->dbConfig->driver == 'dm') return $this->formatDmSQL($sql);

        if(in_array($this->dbConfig->driver, $this->config->pgsqlDriverList)) return $this->formatPgSQL($sql);

        return $sql;
    }

    /**
     * Format pgsql sql.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function formatPgSQL($sql)
    {
        $sql = trim($sql);
        $sql = $this->formatFunction($sql);
        $sql = $this->processDmChangeColumn($sql);
        $sql = $this->processDmTableIndex($sql);

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

                $sql = str_replace('REPLACE', 'INSERT', $sql);
            case 'INSERT':
            case 'UPDATE':
                $setPos = stripos($sql, ' VALUES');
                $sql    = str_replace('0000-00-00', '1970-01-01', $sql);
                $sql    = str_replace('00:00:00', '00:00:01', $sql);
                if(strpos($sql, "\\'") !== false) $sql = str_replace("\\'", "''''", $sql);
                if(strpos($sql, '\"') !== false) $sql = str_replace('\"', '"', $sql);
                if(strpos($sql, '\\\\') !== false) $sql = str_replace('\\\\', '\\', $sql);
                if(stripos($sql, 'CURDATE()')) $sql = str_replace('CURDATE()', 'CURRENT_DATE', $sql);
                break;
            case 'CREATE':
                if(stripos($sql, 'CREATE VIEW') === 0) $sql = str_replace('CREATE VIEW', 'CREATE OR REPLACE VIEW', $sql);
                if(stripos($sql, 'CREATE FUNCTION') === 0) return '';

                if(stripos($sql, 'CREATE OR REPLACE VIEW ') === 0)
                {
                    $sql = $this->formatField($sql);
                    return str_replace('CREATE OR REPLACE VIEW ', 'CREATE VIEW ', $sql);
                }
                elseif(stripos($sql, 'CREATE UNIQUE INDEX') === 0 || stripos($sql, 'CREATE INDEX') === 0)
                {
                    preg_match('/ON\s+([^.`\s]+\.)?`([^\s`]+)`/', $sql, $matches);

                    $tableName = isset($matches[2]) ? str_replace($this->dbConfig->prefix, '', $matches[2]) : '';
                    $sql       = preg_replace('/INDEX\ +\`/', 'INDEX `' . strtolower($tableName) . '_', $sql);
                }

                /* Remove FULLTEXT index. */
                if(stripos($sql, 'FULLTEXT'))
                {
                    $pattern = '/,\s*FULLTEXT\s+KEY\s+`[^`]+`\s*\([^)]+\)\s*/i';
                    $sql = preg_replace($pattern, '', $sql);
                }

                /* Remove comment. */
                $pattern = '/\s+COMMENT\s+[\'"].*?[\'"]\s*/i';
                $sql     = preg_replace($pattern, '', $sql);

                $sql = $this->formatAttr($sql);

            case 'ALTER':
                $sql = $this->formatField($sql);
                $sql = $this->formatAttr($sql);

                return $sql;
            case 'SET':
                if(stripos($sql, 'SET SCHEMA') === 0) return $sql;
            case 'USE':
                return '';
            case 'DESC';
                $tableName = str_ireplace(array('DESC ', '`'), '', $sql);
                $tableName = trim($tableName);
                if(strpos($tableName, ' ') !== false) list($tableName, $columnName) = explode(' ', $tableName);
                $sql = "select COLUMN_NAME as Field, DATA_TYPE as `Type`, DATA_LENGTH as Length, DATA_DEFAULT as `Default`, NULLABLE as `Null` from all_tab_columns where Table_Name='$tableName'";
                if(!empty($columnName)) $sql .= " and COLUMN_NAME='$columnName'";
                return $sql;
            case 'DROP':
                $sql .= ' CASCADE';
                return $this->formatField($sql);
        }

        if($setPos <= 0) return $sql;

        $fields = substr($sql, 0, $setPos);
        $fields = $this->formatField($fields);

        return $fields . substr($sql, $setPos);
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
        $sql = $this->processDmChangeColumn($sql);
        $sql = $this->processDmTableIndex($sql);

        $actionPos = strpos($sql, ' ');
        $action    = strtoupper(substr($sql, 0, $actionPos));
        $setPos    = 0;
        switch($action)
        {
            case 'SELECT':
                return $this->formatField($sql);
            case 'REPLACE':
                $result = $this->processReplace($sql, 'dm');
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
                    $sql = substr($sql, 0, $fieldsBegin+6) . $fields . substr($sql, $fieldsEnd);
                    return str_replace('CREATE OR REPLACE VIEW ', 'CREATE VIEW ', $sql);
                }
                elseif(stripos($sql, 'CREATE UNIQUE INDEX') === 0 || stripos($sql, 'CREATE INDEX') === 0)
                {
                    preg_match('/ON\s+([^.`\s]+\.)?`([^\s`]+)`/', $sql, $matches);

                    $tableName = isset($matches[2]) ? str_replace($this->dbConfig->prefix, '', $matches[2]) : '';
                    $sql       = preg_replace('/INDEX\ +\`/', 'INDEX `' . strtolower($tableName) . '_', $sql);
                }

                /* Remove comment. */
                $pattern = '/\s+COMMENT\s+[\'"].*?[\'"]\s*/i';
                $sql     = preg_replace($pattern, '', $sql);
            case 'ALTER':
                $sql = $this->formatField($sql);
                $sql = $this->formatAttr($sql);
                return $sql;
            case 'SET':
                if(stripos($sql, 'SET SCHEMA') === 0) return $sql;
            case 'USE':
                return '';
            case 'DESC';
                $tableName = str_ireplace(array('DESC ', '`'), '', $sql);
                $tableName = trim($tableName);
                if(strpos($tableName, ' ') !== false) list($tableName, $columnName) = explode(' ', $tableName);
                $sql = "select COLUMN_NAME as Field, DATA_TYPE as `Type`, DATA_LENGTH as Length, DATA_DEFAULT as `Default`, NULLABLE as `Null` from all_tab_columns where Table_Name='$tableName'";
                if(!empty($columnName)) $sql .= " and COLUMN_NAME='$columnName'";
                return $sql;
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
            $tableBegin = strpos($sql, '"' . $this->dbConfig->prefix);
            $tableEnd   = strpos($sql, '"', $tableBegin + 1);
            $tableName  = '' . $this->dbConfig->name . '."' . substr($sql, $tableBegin + 1, $tableEnd - $tableBegin - 1) . '"';
            return "SET IDENTITY_INSERT $tableName ON;" . $sql;
        }

        return $sql;
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
     * Format field.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function formatField($sql)
    {
        if($this->dbConfig->driver == 'dm' || in_array($this->dbConfig->driver, $this->config->pgsqlDriverList))
        {
            $sql = str_replace('`', '"', $sql);
            $sql = preg_replace('/(?<!\w)if\(/i', '"IF"(', $sql);
        }

        return $sql;
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
        /* DATE convert to TO_CHAR. */
        if($this->dbConfig->driver == 'dm') return preg_replace("/\bDATE\(([^)]*)\)/",  "TO_CHAR($1, 'yyyy-mm-dd')", $sql, -1);

        return $sql;
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
        preg_match('/(?<![a-zA-Z\'"])\bif\s*\(.+\)/i', $field, $matches);

        if(empty($matches)) return $field;

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
     * @param  string $sql
     * @access public
     * @return string
     */
    public function formatAttr($sql)
    {
        if($this->dbConfig->driver == 'dm' || in_array($this->dbConfig->driver, $this->config->pgsqlDriverList))
        {
            $pos = stripos($sql, ' ENGINE');
            if($pos > 0) $sql = substr($sql, 0, $pos);

            $sql = preg_replace('/\(\ *\d+\ *\)/', '', $sql);

            $replace = array(
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

            if($this->dbConfig->driver == 'dm')
            {
                $sql = str_ireplace(' AUTO_INCREMENT', ' IDENTITY(1, 1)', $sql);
            }
            else
            {
                $sql = preg_replace('/(\s*`[^`]+`)\s+\K.+AUTO_INCREMENT(,)/i', ' serial,', $sql);
                $sql = str_ireplace(' DATETIME', ' TIMESTAMP', $sql);
            }

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
     * @param mixed  $sql
     * @param string $driver
     * @access public
     * @return void
     */
    public function processReplace($sql, $driver = 'pgsql')
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
        foreach($columns as $index => $column)
        {
            $value  = trim($values[$index], "'");
            $column = trim($column, '`');
            $values[$index]  = $value;
            $columns[$index] = $column;
            if($value != 'NULL') $where[] = "`$column` = '$value'";
        }

        $select_sql = "SELECT * FROM `$table_name` WHERE " . implode(' AND ', $where);

        $result = $this->query($select_sql);
        $result = $result->fetchAll();
        $sql    = in_array('id', $columns) && $driver == 'dm' ? "SET IDENTITY_INSERT `$table_name` ON;" : '';

        if($result)
        {
            // 数据已存在，构造UPDATE语句并执行
            $set   = [];
            $where = [];
            foreach ($columns as $index => $column) {
                $value   = $values[$index];
                $set[]   = $value == 'NULL' ? "`$column` = NULL" : "`$column` = '$value'";
                $where[] = $value == 'NULL' ? "`$column` IS NULL" : "`$column` = '$value'";
            }

            $sql .= "UPDATE `$table_name` SET " . implode(', ', $set) . " WHERE " . implode(' AND ', $where);
        }
        else
        {
            // 数据不存在，构造INSERT INTO语句并执行
            $selectColumn = array();
            $selectValue  = array();
            foreach ($columns as $index => $column) {
                if($values[$index] == 'NULL') continue;

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
     * @return int|false
     */
    public function lastInsertId($name = null)
    {
        $lastInsertID = $this->pdo->lastInsertId($name);
        return $lastInsertID !== false ? (int)$lastInsertID : false;
    }

    /**
     * Begin transaction.
     *
     * @access public
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->pdo->inTransaction() ? false : $this->pdo->beginTransaction();
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
        return $this->pdo->inTransaction() ? $this->pdo->rollBack() : false;
    }

    /**
     * Commit transaction.
     *
     * @access public
     * @return bool
     */
    public function commit()
    {
        return $this->pdo->inTransaction() ? $this->pdo->commit() : false;
    }

    /**
     * 将SQL语句保存到队列中。
     * Save sql to SQLite queue.
     *
     * @param  string $sql
     * @access public
     * @return int|null
     */
    public function pushSqliteQueue(string $sql): int|null
    {
        $allowedActions = array('insert', 'update', 'delete', 'replace');

        $sql       = str_replace(array('\r', '\n'), ' ', trim($sql));
        $actionPos = strpos($sql, ' ');
        $action    = strtolower(substr($sql, 0, $actionPos));

        if(!in_array($action, $allowedActions)) return null;

        foreach($this->dbConfig->sqliteBlacklist as $table)
        {
            $tableName = $this->dbConfig->prefix . $table;
            if(stripos($sql, $tableName) !== false) return null;
        }

        $table  = TABLE_SQLITE_QUEUE;
        $sql    = $this->quote($sql);
        $now    = "now()";
        $action = $this->getLastActionID() + 1;

        $queue = "INSERT INTO $table SET `sql` = $sql, `addDate` = $now, `status` = 'wait', `action` = $action";

        $this->pdo->exec($queue);
        return $this->pdo->lastInsertId();
    }

    /**
     * 获取最后一条动态的id。
     * Get last action id.
     *
     * @access public
     * @return int|false
     */
    public function getLastActionID(): int|false
    {
        $table = TABLE_ACTION;
        $sql = "SELECT id FROM $table ORDER BY id desc limit 1";

        $lastAction = $this->pdo->query($sql)->fetch();
        return $lastAction ? (int)$lastAction->id : false;
    }

    /**
     * 安装时检查数据库用户权限。
     * Check user privilege.
     *
     * @access public
     * @return string
     */
    public function checkUserPriv(): string
    {
        global $config;
        if(!in_array($this->dbConfig->driver, $config->mysqlDriverList)) return '';

        $dbName = $this->dbConfig->name;
        $user   = $this->dbConfig->user;
        $host   = ($this->dbConfig->host == 'localhost' || $this->dbConfig->host == '127.0.0.1') ? 'localhost' : '%';

        $privPairs = array();
        try
        {
            $privList = $this->pdo->query("SHOW GRANTS FOR {$user}@'{$host}';")->fetchAll(PDO::FETCH_COLUMN);
        }
        catch(Exception $e)
        {
            return '';
        }

        foreach($privList as $privSQL)
        {
            if(strpos($privSQL, '*.*') === false && strpos($privSQL, "`$dbName`.*") === false) continue; // 如果权限不是全局或者当前数据库的，跳过
            if(!preg_match('/GRANT (.*) ON (.+) TO/', $privSQL, $matches)) continue;

            $privs = explode(',', $matches[1]);
            foreach($privs as $priv)
            {
                $priv = trim($priv);
                $privPairs[$priv] = true;
            }
        }

        if(isset($privPairs['ALL PRIVILEGES'])) return '';

        // 禅道所需的权限
        $requiredPrivs = array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'CREATE', 'ALTER', 'INDEX', 'CREATE VIEW');
        $missingPrivs  = array_diff($requiredPrivs, array_keys($privPairs));

        if(empty($missingPrivs)) return ''; // 所有权限都满足

        $missingPrivsSQL = implode(', ', $missingPrivs);
        return "GRANT {$missingPrivsSQL} ON `{$dbName}`.* TO {$user}@'{$host}';";
    }

    /**
     * 获取数据库版本。
     * Get version.
     *
     * @access public
     * @return string
     */
    public function getVersion(): string
    {
        if(in_array($this->dbConfig->driver, $this->config->mysqlDriverList))
        {
            $sql = "SELECT VERSION() AS version";
            return $this->rawQuery($sql)->fetch()->version;
        }

        return '';
    }
}
