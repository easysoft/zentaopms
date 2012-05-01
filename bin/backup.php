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
    echo '$config->mysqldumpRoot = \'/usr/bin/mysqldump\'; for linux' . "\n";
    echo '$config->mysqldumpRoot = \'D:\xampp\mysql\bin\mysqldump.exe\'; for windows' . "\n";
    exit;
}

$destDir = $backupDir . "/" . date('Ym', time());

if(!file_exists($backupDir)) mkdir($backupDir, 0777);
if(!file_exists($destDir))   mkdir($destDir, 0777);

$dbSqlFile   = "db." . date('Ymd', time()) . ".sql";

if($config->db->password)
{
    $command = "{$config->mysqldumpRoot} -u{$config->db->user} -p{$config->db->password} -P {$config->db->port} {$config->db->name} > {$dbSqlFile}";
}
else
{
    $command = "{$config->mysqldumpRoot} -u{$config->db->user} -P {$config->db->port} {$config->db->name} > {$dbSqlFile}";
}
echo "Backuping....\n";
system($command, $returnVar);
if(!$returnVar)
{
    $dbZipFile = $destDir . "/"  . str_replace("sql", "zip", $dbSqlFile);
    $archive = new PclZip($dbZipFile);
    $v_list = $archive->create($dbSqlFile);
    if ($v_list == 0) 
    {
        die("Error : ".$archive->errorInfo(true));
    }
    else
    {
        unlink($dbSqlFile);
        echo "Backup DataBase Successfully! The destination file is $dbZipFile\n";
    }
}
else
{
        echo "Failed to Backup DataBase!\n";
}

/* Backup the data. */
$dataFile = $destDir . "/" . "file." . date('Ymd', time()) . ".zip";
chdir(dirname(dirname(__FILE__)) . "/www");
$archive = new PclZip($dataFile);
echo "\nBackuping....\n";
$v_list = $archive->create("data/upload", PCLZIP_OPT_REMOVE_PATH, "data");
if ($v_list == 0) 
{
    die("Error : ".$archive->errorInfo(true));
}
else
{
    echo "Backup www/data Successfully! The destination file is $dataFile\n";
}
