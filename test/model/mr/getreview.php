#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::getReview();
cid=1
pid=1

使用空的repoID, MRID, revision >> return null
使用正确的repoID, MRID >> return normal
使用正确的repoID, MRID, 错误的revision >> return null

*/

$mrModel = $tester->loadModel('mr');

$repoID   = 0;
$MRID     = 0;
$revision = '';

$result = $mrModel->getReview($repoID, $MRID, $revision);
if(empty($result)) $result = 'return null';
r($result) && p() && e('return null'); //使用空的repoID, MRID, revision

$MR     = $tester->dao->select('*')->from(TABLE_MR)->orderBy('id_desc')->limit(1)->fetch();
$repoID = $MR->repoID;
$MRID   = $MR->id;
$result = $mrModel->getReview($repoID, $MRID, $revision);
if(!empty($result))
{
    $first = reset($result);
    if(isset($first['bug']) or isset($first['task'])) $result = 'return normal';
}
r($result) && p() && e('return normal'); //使用正确的repoID, MRID

$revision = '123qwe';
$result   = $mrModel->getReview($repoID, $MRID, $revision);
if(empty($result)) $result = 'return null';
r($result) && p() && e('return null'); //使用正确的repoID, MRID, 错误的revision