#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::saveTask();
cid=1
pid=1

使用空的repoID, MRID >> return false
使用正确的repoID, MRID。空的title。 >> return false
使用正确的repoID, MRID。POST数据正确。 >> Test Task Review

*/

$mrModel = $tester->loadModel('mr');

$_POST = array();
$_POST['title']          = 'Test Task Review';
$_POST['commentText']    = 'Test Task Review';
$_POST['taskModule']     = '1';
$_POST['taskExecution']  = '1';
$_POST['begin']          = '8';
$_POST['end']            = '8';
$_POST['taskAssignedTo'] = '';
$_POST['entry']          = '';

$repoID = 0;
$MRID   = 0;
$v1     = 0;
$v2     = '';
$result = $mrModel->saveTask($repoID, $MRID, $v1, $v2);
if($result['result'] == 'fail' and isset($result['message']['mr']) and isset($result['message']['repo'])) $result = 'return false';
r($result) && p() && e('return false'); //使用空的repoID, MRID

$_POST['title'] = '';
$MR     = $tester->dao->select('*')->from(TABLE_MR)->orderBy('id_desc')->limit(1)->fetch();
$result = $mrModel->saveTask($MR->repoID, $MR->id, $v1, $v2);
if($result['result'] == 'fail' and isset($result['message']['name'])) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的repoID, MRID。空的title。

$_POST['title'] = 'Test Task Review';
$result = $mrModel->saveTask($MR->repoID, $MR->id, $v1, $v2);
r($result) && p('title') && e('Test Task Review'); //使用正确的repoID, MRID。POST数据正确。