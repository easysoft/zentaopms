<?php
/* Backup the db. */
error_reporting(E_ALL ^ E_NOTICE);

/* Include config.php and pclzip class. */
$pmsRoot  = dirname(dirname(dirname(__FILE__)));
$myConfig = $pmsRoot . '/config/config.php';
$zipClass = $pmsRoot . '/lib/pclzip/pclzip.class.php';
$zdbClass = $pmsRoot . '/lib/zdb/zdb.class.php';

include $myConfig;
include $zipClass;
include $zdbClass;

/* Init the backupRoot and dest directory. */
$backupRoot = $pmsRoot . "/backup";
$destDir    = $backupRoot . "/" . date('Ym');

if(!file_exists($backupRoot)) mkdir($backupRoot, 0777);
if(!file_exists($destDir))    mkdir($destDir, 0777);

/* Backup database. */
$dbRawFile = "db." . date('Ymd') . ".sql";
$dsn = "mysql:host={$config->db->host}; port={$config->db->port}; dbname={$config->db->name}";
$dbh = new PDO($dsn, $config->db->user, $config->db->password, array(PDO::ATTR_PERSISTENT => $config->db->persistant));
$dbh->exec("SET NAMES {$config->db->encoding}");
echo "Backuping database,";
$zdb = new zdb();
$return = $zdb->dump($dbRawFile);
if($return->result)
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
