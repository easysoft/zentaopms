#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->deleteProjectWhitelist();
cid=1
pid=1

通过add方法创建一个白名单，这里判断了objectType为sprint的情况，正常传入无法删除 >> 0
传入空的情况时，删除为objectID为0的数据，如果空，则跳过 >> 0

*/

$personnel = new personnelTest('admin');

$projectID = array();
$projectID[0] = 15;
$projectID[1] = '';

$account   = array();
$account[0]   = 'dev15';
$account[1]   = '';

$result1 = $personnel->deleteProjectWhitelistTest($projectID[0], $account[0]);
$result2 = $personnel->deleteProjectWhitelistTest($projectID[1], $account[1]);

r($result1) && p() && e('0'); //通过add方法创建一个白名单，这里判断了objectType为sprint的情况，正常传入无法删除
r($result2) && p() && e('0'); //传入空的情况时，删除为objectID为0的数据，如果空，则跳过