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
     * Database config.
     *
     * @var object
     * @access private
     */
    private $config;

    /**
     * The SQL string of last query.
     *
     * @var string
     * @access private
     */
    private $sql;

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
     * Process PDO/SQL error.
     *
     * @param  object $exception
     * @access public
     * @return void
     */
    public function sqlError(object $exception)
    {
        $sql      = $this->sql;
        $message  = $exception->getMessage();
        $message .= " ,the sql is: '{$sql}'";
        throw new PDOException($message);
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

        if(!empty($this->config->enableSqlite)) $this->pushSqliteQueue($sql);

        try
        {
            if(class_exists('dao')) dao::$querys[] = "[$this->flag] " . dao::processKeywords($sql);
            return $this->pdo->exec($sql);
        }
        catch(PDOException $e)
        {
            $this->sqlError($e);
        }
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
        $sql = $this->formatSQL($sql);
        try
        {
            if(class_exists('dao')) dao::$querys[] = "[$this->flag] " . dao::processKeywords($sql);
            return $this->pdo->query($sql);
        }
        catch(PDOException $e)
        {
            $this->sqlError($e);
        }
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
     * Query raw sql.
     *
     * @param string $sql
     * @access public
     * @return PDOStatement|false
     */
    public function rawQuery($sql)
    {
        try
        {
            if(class_exists('dao')) dao::$querys[] = "[$this->flag] " . dao::processKeywords($sql);
            return $this->pdo->query($sql);
        }
        catch(PDOException $e)
        {
            $this->sqlError($e);
        }
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
                $sql = "SELECT * FROM dba_objects WHERE object_type='SCH' AND owner='{$this->config->name}'";
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
        $sql = "SHOW TABLES FROM {$this->config->name} like $tableName";
        switch($this->config->driver)
        {
            case 'mysql':
                $sql = "SHOW TABLES FROM {$this->config->name} like {$tableName}";
                break;
            case 'dm':
                $sql = "SELECT * FROM all_tables WHERE owner='{$this->config->name}' AND table_name={$tableName}";
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
                $tableSpace = strtoupper($this->config->name);
                $res = $this->rawQuery("SELECT * FROM dba_data_files WHERE TABLESPACE_NAME = '$tableSpace'")->fetchAll();

                if(empty($res))
                {
                    $createTableSpace = "CREATE TABLESPACE $tableSpace DATAFILE '{$this->config->name}.DBF' size 150 AUTOEXTEND ON";
                    $createUser       = "CREATE USER {$this->config->name} IDENTIFIED by {$this->config->password} DEFAULT TABLESPACE {$this->config->name} DEFAULT INDEX TABLESPACE {$this->config->name}";

                    $this->rawQuery($createTableSpace);
                    $this->rawQuery($createUser);
                }

                $createSchema = "CREATE SCHEMA {$this->config->name} AUTHORIZATION {$this->config->name}";
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
        $this->sql = $sql;

        switch($this->config->driver)
        {
            case 'dm':
                return $this->formatDmSQL($sql);
                return $sql;

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
        $sql       = trim($sql);
        $sql       = str_replace(array('\r', '\n'), ' ', $sql);
        $actionPos = strpos($sql, ' ');
        $action    = strtoupper(substr($sql, 0, $actionPos));
        $setPos    = 0;
        switch($action)
        {
            case 'SELECT':
                return $this->formatField($sql);
            case 'REPLACE':
                $sql = str_replace('REPLACE', 'INSERT', $sql);
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
                if(stripos($sql, 'CREATE VIEW') === 0) return '';
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
                elseif(stripos($sql, 'CREATE UNIQUE INDEX') === 0)
                {
                    preg_match('/ON\ +[0-9a-zA-Z\_\.]+\`([0-9a-zA-Z\_]+)\`/', $sql, $matches);
                    $tableName = explode('_', $matches[1]);
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
        }

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
        return $this->pdo->beginTransaction();
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

        foreach($this->config->sqliteBlacklist as $table)
        {
            $tableName = $this->config->prefix . $table;
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
}
