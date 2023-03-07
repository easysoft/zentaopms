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
     * @access public
     * @return void
     */
    public function __construct($config)
    {
        $dsn = "{$config->driver}:host={$config->host}:{$config->port}";
        $pdo = new PDO($dsn, $config->user, $config->password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if($config->driver == 'mysql')
        {
            $pdo->exec("SET NAMES {$config->encoding}");
            if(isset($this->config->strictMode) and $this->config->strictMode == false) $pdo->exec("SET @@sql_mode= ''");
        }

        $this->pdo = $pdo;
        $this->config = $config;
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
        return $this->pdo->query($sql);
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
        return $this->pdo->exec($sql);
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
                $sql = "select * from dba_objects where object_type='SCH' and owner='{$this->config->name}';";
                break;
            default:
                $sql = '';
        }

        return $this->query($sql)->fetch();
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
                $sql = "select * from all_tables where owner='{$this->config->name}' and table_name={$tableName};";
                break;
            default:
                $sql = '';
        }

        return $this->query($sql)->fetch();
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
        switch($this->config->driver == 'mysql')
        {
            case 'mysql':
                $sql = "CREATE DATABASE `{$this->config->name}`";
                if($version > 4.1) $sql .= " DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
                return $this->query($sql);

            case 'dm':
                $createTableSpace = "CREATE TABLESPACE {$this->config->name} DATAFILE '{$this->config->name}.DBF' size 150 AUTOEXTEND ON";
                $createUser       = "CREATE USER {$this->config->name} IDENTIFIED by {$this->config->password} DEFAULT TABLESPACE {$this->config->name} DEFAULT INDEX TABLESPACE {$this->config->name};";

                $this->query($createTableSpace);
                return $this->query($createUser);

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
        switch($this->config->driver == 'mysql')
        {
            case 'mysql':
                return $this->exec("USE {$this->config->name}");

            case 'dm':
                return $this->exec("SET SCHEMA {$this->config->name}");

            default:
                return false;
        }
    }
}
