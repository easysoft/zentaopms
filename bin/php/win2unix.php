<?php
error_reporting(E_ALL);
include dirname(dirname(dirname(__FILE__))) . "/config/config.php";

$renameTables = array('zt_casestep' => 'zt_caseStep', 'zt_doclib' => 'zt_docLib', 'zt_grouppriv' => 'zt_groupPriv',
'zt_productplan' => 'zt_productPlan', 'zt_projectproduct' => 'zt_projectProduct', 'zt_projectstory' => 'zt_projectStory',
'zt_storyspec' => 'zt_storySpec', 'zt_taskestimate' => 'zt_taskEstimate', 'zt_testresult' => 'zt_testResult',
'zt_testrun' => 'zt_testRun', 'zt_testtask' => 'zt_testTask', 'zt_usercontact' => 'zt_userContact', 'zt_usergroup' => 'zt_userGroup',
'zt_userquery' => 'zt_userQuery', 'zt_usertpl' => 'zt_userTPL');

$renameTables += array('zt_relationoftasks' => 'zt_relationOfTasks', 'zt_repohistory' => 'zt_repoHistory');

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

$existTables = $dbh->query('SHOW TABLES')->fetchAll();
foreach($existTables as $key => $table) $existTables[$key] = current((array)$table);
$existTables = array_flip($existTables);

foreach($renameTables as $oldTable => $newTable)
{
    if(isset($existTables[$newTable]))
    {
        echo "Has existed table '$newTable'\n";
    }
    elseif(!isset($existTables[$oldTable]))
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
