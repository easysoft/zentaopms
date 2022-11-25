#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::getLastReviewInfo();
cid=1
pid=1

使用空的repoID >> return null
使用正确的repoID >> return normal

*/

$mrModel = $tester->loadModel('mr');

$repoID = 0;
$result = $mrModel->getLastReviewInfo($repoID);
if(empty($result)) $result = 'return null';
r($result) && p() && e('return null'); //使用空的repoID

$repo   = $tester->dao->select('*')->from(TABLE_REPO)->orderBy('id_desc')->limit(1)->fetch();
$result = $mrModel->getLastReviewInfo($repo->id);
if(isset($result->bug) and isset($result->task)) $result = 'return normal';
r($result) && p() && e('return normal'); //使用正确的repoID