<?php
/**
 * The zdb library of zentaopms. This lib operate db.
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
     * @var int   
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
     * Backup db. 
     * 
     * @param  string $fileName 
     * @param  array  $tables 
     * @access public
     * @return string|int
     */
    public function backupDB($fileName, $tables = array())
    {
        if(empty($tables))
        {
            $stmt = $this->dbh->query('show tables');
            while($table = $stmt->fetch(PDO::FETCH_ASSOC)) $tables[] = current($table);
        }

        if(empty($fileName)) return 'Has not file';
        if(!is_writable(dirname($fileName))) return 'The directory is not writable';
        $fp = fopen($fileName, 'w');

        fwrite($fp, "SET NAMES utf8;\n");
        foreach($tables as $table)
        {
            $backupSql = "DROP TABLE IF EXISTS `$table`;\n";

            $createSql  = $this->dbh->query("show create table `$table`")->fetch(PDO::FETCH_ASSOC);
            $backupSql .= $createSql['Create Table'] . ";\n";

            $datas = $this->dbh->query("select * from `$table`")->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($datas))
            {
                $keys = array_keys(current($datas));
                $keys = array_map('addslashes', $keys);
                $keys = join('`,`', $keys);
                $keys = "`" . $keys . "`";

                $values = array();
                foreach($datas as $data)
                {
                    $value = array_values($data);
                    $value = array_map('addslashes', $value);
                    $value = join("','", $value);
                    $value = "'" . $value . "'";

                    $values[] = "($value)";
                }
                $backupSql .= "INSERT INTO `$table`($keys) VALUES" . join(',', $values) . ";\n";
            }

            fwrite($fp, $backupSql);
        }
        fclose ($fp);

        return 0;
    }
}
