<?php
include '../../config/config.php';
connectDB();

$databases[] = 'zentao_03';
$databases[] = 'zentao_04';
$databases[] = 'zentao_05';
$databases[] = 'zentao_06';
$databases[] = 'zentao_10';
$databases[] = 'zentao_101';
$databases[] = 'zentao_11';
$databases[] = 'zentao_12';
$databases[] = 'zentao_13';
$databases[] = 'zentao_14';
$databases[] = 'zentao_15';

if(!isset($argv[1])) die(__FILE__ . ' restore' . "\n");

$cmd = $argv[1];
if($cmd == 'backup') backupDB();
if($cmd == 'restore') restore();

/* Backup new installed database. */
function backupDB()
{
    global $databases;
    foreach($databases as $database)
    {
        $backupDatabase = "back_$database";
        mysql_query("DROP DATABASE $backupDatabase");
        mysql_query("CREATE DATABASE $backupDatabase");
        $cmd = "mysqldump -u root $database > $database.sql;\n";
        $cmd .= "mysql -uroot $backupDatabase < $database.sql;\n";
        $cmd .= "rm -fr $database.sql";
        system($cmd);
    }
}

/* Restore stored backup databases. */
function restore()
{
    global $databases;
    foreach($databases as $database)
    {
        $backupDatabase = "back_$database";
        mysql_query("DROP DATABASE $database");
        mysql_query("CREATE DATABASE $database");
        $cmd = "mysqldump -u root $backupDatabase > $database.sql;\n";
        $cmd .= "mysql -uroot $database < $database.sql;\n";
        $cmd .= "rm -fr $database.sql";
        system($cmd);
    }
}

/* Connect to database. */
function connectDB()
{
    global $config;
    mysql_connect($config->db->host, $config->db->user, $config->db->password);
}
