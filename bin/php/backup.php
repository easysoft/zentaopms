<?php
/* Backup the db. */
error_reporting(E_ERROR);

/* Include my.php and pclzip class. */
$pmsRoot = dirname(dirname(dirname(__FILE__)));
include $pmsRoot . '/config/my.php';
include $pmsRoot . '/lib/pclzip/pclzip.class.php';

/* Judge mysqldump cmd setted or not. */
if(!isset($config->mysqldump))
{
    echo "Please set the mysqldump in my.php:\n";
    echo "Just like: \n";
    echo '$config->mysqldump = \'/usr/bin/mysqldump\'; for linux' . "\n";
    echo '$config->mysqldump = \'D:\xampp\mysql\bin\mysqldump.exe\'; for windows' . "\n";
    exit;
}

/* Init the backupRoot and dest directory. */
$backupRoot = $pmsRoot . "/backup";
$destDir    = $backupRoot . "/" . date('Ym');

if(!file_exists($backupRoot)) mkdir($backupRoot, 0777);
if(!file_exists($destDir))    mkdir($destDir, 0777);

/* Backup database. */
$dbRawFile = "db." . date('Ymd') . ".sql";
if($config->db->password)
{
    $command = "{$config->mysqldump} -u{$config->db->user} -p{$config->db->password} -P {$config->db->port} {$config->db->name} > {$dbRawFile}";
}
else
{
    $command = "{$config->mysqldump} -u{$config->db->user} -P {$config->db->port} {$config->db->name} > {$dbRawFile}";
}
echo "Backuping database,";
system($command, $return);
if(!$return)
{
    $dbZipFile = $destDir . "/"  . str_replace("sql", "zip", $dbRawFile);
    $archive = new pclzip($dbZipFile);
    if($archive->create($dbRawFile))
    {
        unlink($dbRawFile);
        echo " successfully saved to $dbZipFile\n";
    }
    else
    {
        die("Error : " . $archive->errorInfo(true));
    }
}
else
{
    echo "Failed to backup database!\n";
}

/* Backup the data. */
chdir(dirname(dirname(dirname(__FILE__))) . "/www");
if(!is_dir('data/upload')) die(" No files needed backup.\n");

$dataFile = $destDir . "/" . "file." . date('Ymd', time()) . ".zip";
$archive  = new pclzip($dataFile);
echo "Backuping files,";
if($archive->create("data/upload", PCLZIP_OPT_REMOVE_PATH, "data")) die(" successfully saved to $dataFile\n");
die("Error : ".$archive->errorInfo(true));
