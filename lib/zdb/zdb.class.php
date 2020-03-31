<?php
/**
 * The zdb library of zentaopms, can be used to bakup and restore a database.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     Zdb
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class zdb
{
    /**
     * dbh
     *
     * @var object
     * @access public
     */
    public $dbh;

    /**
     * Construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $dbh;
        $this->dbh = $dbh;
    }

    /**
     * Get all tables.
     *
     * @param  string $type  if type is 'base', just get base table.
     * @access public
     * @return array
     */
    public function getAllTables($type = 'base')
    {
        global $config;

        $allTables = array();
        $stmt      = $this->dbh->query("show full tables");
        while($table = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $tableType = strtolower($table['Table_type']);
            if($type == 'base' and $tableType != 'base table') continue;

            $tableName = $table["Tables_in_{$config->db->name}"];
            $allTables[$tableName] = $tableType == 'base table' ? 'table' : $tableType;
        }

        return $allTables;
    }

    /**
     * Get table fields.
     *
     * @param  string $table
     * @access public
     * @return array
     */
    public function getTableFields($table)
    {
        try
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            $sql = "DESC $table";
            $rawFields = $this->dbh->query($sql)->fetchAll();
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        }
        catch (PDOException $e)
        {
            global $dao;
            $dao->sqlError($e);
        }

        $fields = array();
        foreach($rawFields as $field) $fields[$field->field] = $field;

        return $fields;
    }

    /**
     * Diff current table fields with a fields array.
     *
     * @param  string $table
     * @param  array  $fields
     * @access public
     * @return array
     */
    public function diffTable($table, $fields)
    {
        $tableFields = $this->getTableFields($table);

        $diff = array_udiff_assoc($fields, $tableFields,
            function($a, $b)
            {
                return (array)$a == (array)$b ? 0 : 1;
            }
        );

        return $diff;
    }

    /**
     * Add a column to a table, or modify a existing column.
     *
     * @param  string  $table
     * @param  object  $column
     * @param  boolean $add    if true, add $column as a new column, otherwise modify a existing column to $column.
     * @access public
     * @return object
     */
    public function updateColumn($table, $column, $add = true)
    {
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';

        $query = "ALTER TABLE `$table` " . ($add ? 'ADD' : 'MODIFY COLUMN') . " `$column->field` $column->type" . ($column->null == 'NO' ? ' NOT NULL' : '') . (is_null($column->default) ? '' : " DEFAULT '$column->default'") . (empty($column->extra) ? '' : " $column->extra") . ';';

        try
        {
            $this->dbh->exec($query);
            return $return;
        }
        catch(PDOException $e)
        {
            $return->result = false;
            $return->error  = $e->getMessage();
            $return->sql    = $query;
            return $return;
        }
    }

    /**
     * Create a table with fields.
     *
     * @param  string $name
     * @param  array  $fields
     * @access public
     * @return object
     */
    public function createTable($name, $fields)
    {
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';
        $createTableQuery = "CREATE TABLE `$name` (";

        foreach($fields as $field)
        {
            $createColumnQuery = "`$field->field` $field->type" . ($field->null == 'NO' ? ' NOT NULL' : '') . (is_null($field->default) ? '' : " DEFAULT '$field->default'") . (empty($field->extra) ? '' : " $field->extra") . ", ";
            if(!empty($field->key))
            {
                if($field->key === 'PRI') $createColumnQuery .= "PRIMARY KEY (`{$field->field}`), ";
                if($field->key === 'MUL') $createColumnQuery .= "KEY `{$field->field}` (`{$field->field}`), ";
                if($field->key === 'UNI') $createColumnQuery .= "UNIQUE KEY `{$field->field}` (`{$field->field}`), ";
            }
            $createTableQuery .= $createColumnQuery;
        }

        $createTableQuery = rtrim($createTableQuery, ', ');
        $createTableQuery .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";

        try
        {
            $this->dbh->exec($createTableQuery);
            return $return;
        }
        catch(PDOException $e)
        {
            $return->result = false;
            $return->error  = $e->getMessage();
            $return->sql    = $createTableQuery;
            return $return;
        }
    }

    /**
     * Dump db.
     *
     * @param  string $fileName
     * @param  array  $tables
     * @access public
     * @return object
     */
    public function dump($fileName, $tables = array())
    {
        /* Init the return. */
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';

        /* Get all tables in database. */
        $allTables = $this->getAllTables();

        /* Dump all tables when tables is empty. */
        if(empty($tables))
        {
            $tables = $allTables;
        }
        else
        {
            foreach($tables as $table) $tables[$table] = $allTables[$table];
        }

        /* Check file. */
        if(empty($fileName))
        {
            $return->result = false;
            $return->error  = 'Has not file';
            return $return;
        }
        if(!is_writable(dirname($fileName)))
        {
            $return->result = false;
            $return->error  = 'The directory is not writable';
            return $return;
        }

        /* Open this file. */
        $fp = fopen($fileName, 'w');
        fwrite($fp, "SET NAMES utf8;\n");

        $this->dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        foreach($tables as $table => $tableType)
        {
            /* Check table exists. */
            if(!isset($allTables[$table])) continue;

            /* Create sql code. */
            $backupSql  = "DROP " . strtoupper($tableType) . " IF EXISTS `$table`;\n";

            $schemaSQL = $this->getSchemaSQL($table, $tableType);
            if($schemaSQL->result) $backupSql .= $schemaSQL->sql;

            fwrite($fp, $backupSql);
            if($tableType != 'table') continue;

            $rows = $this->dbh->query("select * from `$table`");
            while($row = $rows->fetch(PDO::FETCH_ASSOC))
            {
                /* Create key sql for insert. */
                $keys = array_keys($row);
                $keys = array_map('addslashes', $keys);
                $keys = join('`,`', $keys);
                $keys = "`" . $keys . "`";

                /* Create a value sql. */
                $value = array_values($row);
                $value = array_map('addslashes', $value);
                $value = join("','", $value);
                $value = "'" . $value . "'";
                $sql   = "INSERT INTO `$table`($keys) VALUES (" . $value . ");\n";

                /* Write sql code. */
                fwrite($fp, $sql);
            }
        }
        fclose($fp);
        return $return;
    }

    /**
     * Import DB
     *
     * @param  string $fileName
     * @access public
     * @return object
     */
    public function import($fileName)
    {
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';

        if(!file_exists($fileName))
        {
            $return->result = false;
            $return->error  = "File is not exists";
            return $return;
        }

        $fp     = fopen($fileName, 'r');
        $sqlEnd = 0;
        while(($buffer = fgets($fp)) !== false)
        {
            $line = trim($buffer);
            if(empty($line)) continue;

            if($sqlEnd == 0) $sql = '';
            $quotNum = substr_count($line, "'") - substr_count($line, "\'");
            if(substr($line, -1) == ';' and $quotNum % 2 == 0 and $sqlEnd == 0)
            {
                $sql .= $buffer;
            }
            elseif($quotNum % 2 == 1 and $sqlEnd == 0)
            {
                $sql   .= $buffer;
                $sqlEnd = 1;
            }
            elseif(substr($line, -1) == ';' and $quotNum % 2 == 1 and $sqlEnd == 1)
            {
                $sql   .= $buffer;
                $sqlEnd = 0;
            }
            elseif(substr($line, -1) == ';' and $quotNum % 2 == 0 and $sqlEnd == 2)
            {
                $sql   .= $buffer;
                $sqlEnd = 0;
            }
            else
            {
                $sql .= $buffer;
                $sqlEnd = $sqlEnd == 0 ? 2 : $sqlEnd;
            }

            if($sqlEnd == 0)
            {
                try
                {
                    $this->dbh->query($sql);
                }
                catch(PDOException $e)
                {
                    $return->result = false;
                    $return->error .= $e->getMessage() . "\n";
                }
            }
        }
        return $return;
    }

    /**
     * Get schema SQL.
     *
     * @param  string $table
     * @access public
     * @return object
     */
    public function getSchemaSQL($table, $type = 'table')
    {
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';

        try
        {
            $sql = "SHOW CREATE $type `$table`";
            $createSql   = $this->dbh->query($sql)->fetch(PDO::FETCH_ASSOC);
            $return->sql = $createSql['Create ' . ucfirst($type)] . ";\n";
            return $return;
        }
        catch(PDOException $e)
        {
            $return->result = false;
            $return->error  = $e->getMessage();
            return $return;
        }
    }
}
