<?php
/**
 * The zdb library of zentaopms, can be used to bakup and restore a database.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
        $sql       = 'show full tables';

        if($config->db->driver == 'dm') $sql = "select OBJECT_NAME AS Tables_in_{$config->db->name}, OBJECT_TYPE as Table_type from all_objects where owner='{$config->db->name}' and OBJECT_TYPE in('TABLE','VIEW');";
        $stmt = $this->dbh->query($sql);

        while($table = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $tableType = strtolower($table['Table_type']);
            if($type == 'base' && $tableType != 'base table' && $tableType != 'table') continue;

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
            $desc       = $this->dbh->query("desc `$table`")->fetchAll();
            if(empty($desc)) continue;

            $schemaSQL = $this->getSchemaSQL($table, $tableType);
            if($schemaSQL->result) $backupSql .= $schemaSQL->sql;

            fwrite($fp, $backupSql);
            if($tableType != 'table') continue;

            $nullFields = array();
            foreach($desc as $field) $nullFields[$field->Field] = ($field->Null == 'YES' ||  $field->Null == 'Y');

            /* Create key sql for insert. */
            $fields   = "`" . join('`,`', array_map('addslashes', array_keys($nullFields))) . "`";
            $rows     = $this->dbh->query("select * from `$table`");
            $values   = array();
            $batchNum = 200;
            while($row = $rows->fetch(PDO::FETCH_ASSOC))
            {
                /* Create a value sql. */
                $row   = array_map('addslashes', $row);
                $value = array();
                foreach($row as $fieldName => $fieldValue)
                {
                    $length     = strlen($fieldValue);
                    $fieldValue = "'{$fieldValue}'";
                    if($length == 0 and !empty($nullFields[$fieldName])) $fieldValue = 'null';

                    $value[] = $fieldValue;
                }

                $values[] = '(' . join(',', $value) . ')';
                if(count($values) == $batchNum)
                {
                    /* Write sql code. */
                    fwrite($fp, "INSERT INTO `$table`($fields) VALUES " . implode(",\n", $values) . ";\n");
                    $values = array();
                }
            }
            if($values) fwrite($fp, "INSERT INTO `$table`($fields) VALUES " . implode(",\n", $values) . ";\n");
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

        $fp        = fopen($fileName, 'r');
        $sql       = '';
        $startTags = '^DROP TABLE|^CREATE TABLE|^INSERT INTO|^SET';
        $isInsert  = false;
        while(!feof($fp))
        {
            $line = fgets($fp);
            if(empty($line)) continue;

            $sqlStart = false;
            $sqlEnd   = false;
            $execSQL  = false;
            if(empty($sql) and preg_match("/{$startTags}/", $line)) $sqlStart = true;
            if(!$isInsert and $sqlStart and strpos($line, 'INSERT INTO') === 0) $isInsert = true;

            $endTag = $isInsert ? "[^\\\]\'\);$" : ";$";
            if(preg_match("/{$endTag}/", $line)) $sqlEnd = true;

            if($sqlStart && $sqlEnd)        // Only one line sql. e.g. DROP TABLE IF EXISTS `blog`;
            {
                $sql     = $line;
                $execSQL = true;
            }
            elseif($sqlStart && !$sqlEnd)   // Start sql line. e.g. CREATE TABLE `zt_account` (
            {
                $sql     = $line;
                $execSQL = false;
            }
            elseif(!$sqlStart && !$sqlEnd)  // Not start and not end. e.g.  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
            {
                $sql    .= $line;
                $execSQL = false;
            }
            elseif($sqlEnd)                 // More line sql, and end line. e.g. ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            {
                $sql    .= $line;
                $execSQL = true;
            }

            if($execSQL)
            {
                try
                {
                    $this->dbh->exec($sql);
                }
                catch(PDOException $e)
                {
                    $return->result = false;
                    $return->error .= $e->getMessage() . "\n";
                }
                $sql      = '';
                $isInsert = false;
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

    /**
     * Add slashes for string or string list.
     *
     * @param  string|string[] $data
     * @return string|string[]
     */
    public function addslashes($data)
    {
        if(is_string($data)) return addslashes($data);
        if((function_exists('array_is_list') && array_is_list($data)) || (is_array($data) && array_keys($data) === array_keys(array_keys($data))))
        {
            $result = array();
            foreach($data as $item)
            {
                if(is_string($item))
                    $result[] = addslashes($item);
                elseif(is_null($item))
                    $result[] = null;
                else
                    $result[] = $item;
            }
            return $result;
        }
        return $data;
    }
}
