<?php
error_reporting(E_ALL);
include dirname(dirname(dirname(__FILE__))) . "/config/config.php";

$tables2Rename = array();
$tables2Rename['zt_casestep']       = 'zt_caseStep';
$tables2Rename['zt_doclib']         = 'zt_docLib';
$tables2Rename['zt_grouppriv']      = 'zt_groupPriv';
$tables2Rename['zt_productplan']    = 'zt_productPlan';
$tables2Rename['zt_projectproduct'] = 'zt_projectProduct';
$tables2Rename['zt_projectstory']   = 'zt_projectStory';
$tables2Rename['zt_storyspec']      = 'zt_storySpec';
$tables2Rename['zt_taskestimate']   = 'zt_taskEstimate';
$tables2Rename['zt_testresult']     = 'zt_testResult';
$tables2Rename['zt_testrun']        = 'zt_testRun';
$tables2Rename['zt_testtask']       = 'zt_testTask';
$tables2Rename['zt_usercontact']    = 'zt_userContact';
$tables2Rename['zt_usergroup']      = 'zt_userGroup';
$tables2Rename['zt_userquery']      = 'zt_userQuery';
$tables2Rename['zt_usertpl']        = 'zt_userTPL';

/* Zentao Pro table. */
$tables2Rename['zt_relationoftasks'] = 'zt_relationOfTasks';
$tables2Rename['zt_repohistory']     = 'zt_repoHistory';

try
{
    $params = $config->db;
    $dbh = new PDO("mysql:host={$params->host}; port={$params->port}; dbname={$params->name}", $params->user, $params->password);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->exec("SET NAMES utf8");
}
catch(PDOException $e)
{
    echo 'Connection failed: ' . $e->getMessage() . "\n";
    die("connect to db failed.\n");
}

$tablesExists = $dbh->query('SHOW TABLES')->fetchAll();
foreach($tablesExists as $key => $table) $tablesExists[$key] = current((array)$table);
$tablesExists = array_flip($tablesExists);

foreach($tables2Rename as $oldTable => $newTable)
{
    if(isset($tablesExists[$newTable]))
    {
        echo "Has existed table '$newTable'\n";
    }
    elseif(!isset($tablesExists[$oldTable]))
    {
        echo "No found table '$oldTable'\n";
    }
    else
    {
        $dbh->query("RENAME TABLE `$oldTable` TO `$newTable`");
        echo "RENAME TABLE `$oldTable` TO `$newTable`\n";
    }
}
echo "Finish!\n";
