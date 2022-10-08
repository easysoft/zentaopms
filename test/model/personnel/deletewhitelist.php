#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->deleteWhitelist();
cid=1
pid=1

创建删除objectID为2的项目白名单 >> 0
创建删除objectID为2的产品白名单 >> 0
创建删除objectID为10的项目集白名单 >> 0
创建删除objectID为11的执行白名单 >> 0

*/

$personnel = new personnelTest('admin');

$users      = array();
$users[0]      = 'dev17';

$objectType = array();
$objectType[0] = 'project';
$objectType[1] = 'product';
$objectType[2] = 'program';
$objectType[3] = 'sprint';

$objectID   = array();
$objectID[0]   = '2';
$objectID[1]   = '10';
$objectID[2]   = '11';

$groupID    = array();
$groupID[0]    = '1';
$groupID[1]    = '2';

$result1 = $personnel->deleteWhitelistTest($users, $objectType[0], $objectID[0]);
$result2 = $personnel->deleteWhitelistTest($users, $objectType[1], $objectID[0]);
$result3 = $personnel->deleteWhitelistTest($users, $objectType[2], $objectID[1]);
$result4 = $personnel->deleteWhitelistTest($users, $objectType[3], $objectID[2]);

r($result1) && p() && e('0'); //创建删除objectID为2的项目白名单
r($result2) && p() && e('0'); //创建删除objectID为2的产品白名单
r($result3) && p() && e('0'); //创建删除objectID为10的项目集白名单
r($result4) && p() && e('0'); //创建删除objectID为11的执行白名单