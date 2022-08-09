#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::getDiffs();
cid=1
pid=1

使用空的MR >> return null
使用正确的MR >> return normal

*/

$mrModel = $tester->loadModel('mr');

$MR     = '';
$result = $mrModel->getDiffs($MR);
if(empty($result)) $result = 'return null';
r($result) && p() && e('return null'); //使用空的MR

$MR     = $tester->dao->select('*')->from(TABLE_MR)->orderBy('id_desc')->limit(1)->fetch();
$result = $mrModel->getDiffs($MR);
if(!empty($result))
{
    $first = reset($result);
    if(isset($first->fileName) and is_array($first->contents)) $result = 'return normal';
}
r($result) && p() && e('return normal'); //使用正确的MR