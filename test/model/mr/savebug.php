#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::saveBug();
cid=1
pid=1

使用空的repoID, MRID >> return false
使用正确的repoID, MRID。空的title。 >> return false
使用正确的repoID, MRID。POST数据正确。 >> Test Bug Review

*/

$mrModel = $tester->loadModel('mr');

$_POST = array();
$_POST['title']       = 'Test Bug Review';
$_POST['commentText'] = 'Test Bug Review';
$_POST['module']      = '1';
$_POST['begin']       = '8';
$_POST['end']         = '8';
$_POST['product']     = '1';
$_POST['assignedTo']  = '';

$repoID = 0;
$MRID   = 0;
$v1     = 0;
$v2     = '';
$result = $mrModel->saveBug($repoID, $MRID, $v1, $v2);
if($result['result'] == 'fail' and isset($result['message']['mr']) and isset($result['message']['repo'])) $result = 'return false';
r($result) && p() && e('return false'); //使用空的repoID, MRID

$_POST['title'] = '';
$MR     = $tester->dao->select('*')->from(TABLE_MR)->orderBy('id_desc')->limit(1)->fetch();
$result = $mrModel->saveBug($MR->repoID, $MR->id, $v1, $v2);
if($result['result'] == 'fail' and isset($result['message']['title'])) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的repoID, MRID。空的title。

$_POST['title'] = 'Test Bug Review';
$result = $mrModel->saveBug($MR->repoID, $MR->id, $v1, $v2);
r($result) && p('title') && e('Test Bug Review'); //使用正确的repoID, MRID。POST数据正确。