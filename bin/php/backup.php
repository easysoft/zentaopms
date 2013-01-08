<?php
/* Backup the db. */
error_reporting(E_ERROR);

/* Include my.php and pclzip class. */
$pmsRoot  = dirname(dirname(dirname(__FILE__)));
$myConfig = $pmsRoot . '/config/my.php';
$zipClass = $pmsRoot . '/lib/pclzip/pclzip.class.php';

include $myConfig;
include $zipClass;

/* Judge mysqldump cmd setted or not. */
if(empty($config->mysqldump))
{
    echo "Please set the mysqldump path in $myConfig:\n";
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
$password  = $config->db->password ?  "-p{$config->db->password}" : ' ';
$command   = "{$config->mysqldump} -u{$config->db->user} $password -P {$config->db->port} {$config->db->name} > {$dbRawFile}";

echo "Backuping database,";
system($command, $return);
if($return == 0)
{
    $dbZipFile = $destDir . "/"  . str_replace("sql", "zip", $dbRawFile);
    $archive  = new pclzip($dbZipFile);

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

/* Backup the attachments. */
chdir(dirname(dirname(dirname(__FILE__))) . "/www");
if(!is_dir('data/upload')) die(" No files needed backup.\n");

echo "Backuping files,";
$attachFile = $destDir . "/" . "file." . date('Ymd', time()) . ".zip";
$archive    = new pclzip($attachFile);
if($archive->create("data/upload", PCLZIP_OPT_REMOVE_PATH, "data")) die(" successfully saved to $attachFile\n");
die("Error : ".$archive->errorInfo(true));
