<?php
/**
 * The zdb library of zentaopms, can be used to bakup and restore a database.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
        $allTables = array();
        $stmt      = $this->dbh->query('show tables');
        while($table = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $table = current($table);
            $allTables[$table] = $table;
        }

        /* Dump all tables when tables is empty. */
        if(empty($tables)) $tables = $allTables;

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
        foreach($tables as $table)
        {
            /* Check table exists. */
            if(!isset($allTables[$table])) continue;

            /* Create sql code. */
            $backupSql  = "DROP TABLE IF EXISTS `$table`;\n";
            $backupSql .= $this->getSchemaSQL($table);
            fwrite($fp, $backupSql);

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
     * @access public
     * @return object;
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
                    $return->error  = $e->getMessage();
                    return $return;
                }
            }
        }
        return $return;
    }

    /**
     * Get schema SQL.
     * 
     * @param  string    $table 
     * @access public
     * @return string
     */
    public function getSchemaSQL($table)
    {
        $createSql = $this->dbh->query("show create table `$table`")->fetch(PDO::FETCH_ASSOC);
        return $createSql['Create Table'] . ";\n";
    }
}
