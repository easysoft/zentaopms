#!/usr/bin/env php
<?php
/* Backup the db. */
$pmsRoot   = dirname(dirname(__FILE__));
$backupDir = $pmsRoot . "/backup";

include $pmsRoot . '/config/my.php';
include $pmsRoot . '/lib/pclzip/pclzip.class.php';

if(!isset($config->mysqldumpRoot))
{
    echo "Please set the mysqldumpRoot in my.php:\n";
    echo "Just like: \n";
    echo '$config->mysqldumpRoot = \'/user/bin/mysqldump\' for linux' . "\n";
    echo '$config->mysqldumpRoot = \'D:\xampp\mysql\bin\' for windows';
    exit;
}

$destDir = $backupDir . "/" . date('Ym', time());

if(!file_exists($backupDir)) mkdir($backupDir, 0777);
if(!file_exists($destDir))   mkdir($destDir, 0777);

$dbSqlFile   = $destDir . "/" ."db." . date('Ymd', time()) . ".sql";

if($config->db->password)
{
    $command = "{$config->mysqldumpRoot} -u{$config->db->user} -p{$config->db->password} {$config->db->name} > {$dbSqlFile}";
}
else
{
    $command = "{$config->mysqldumpRoot} -u{$config->db->user} {$config->db->name} > {$dbSqlFile}";
}
echo "Backuping....\n";
exec($command);

$dbZipFile = str_replace("sql", "zip", $dbSqlFile);
$archive = new PclZip($dbZipFile);
$v_list = $archive->create($dbSqlFile);
if ($v_list == 0) 
{
    die("Error : ".$archive->errorInfo(true));
}
else
{
    unlink($dbSqlFile);
    echo "Backup DataBase Successfully!\n";
}

/* Backup the data. */
$dataFile = $destDir . "/" . "file." . date('Ymd', time()) . ".zip";
$archive = new PclZip($dataFile);
echo "Backuping....\n";
$v_list = $archive->create(dirname(dirname(__FILE__)) . "/www/data");
if ($v_list == 0) 
{
    die("Error : ".$archive->errorInfo(true));
}
else
{
    echo "Backup www/data Successfully!\n";
}

?>

