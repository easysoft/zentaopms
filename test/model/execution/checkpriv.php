#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试 executionModel->checkPriv();
cid=1
pid=1

测试传入空值 >> 0
测试传入0 >> 0
测试传入正确的项目ID的权限判断 >> 1
测试传入迭代ID的权限判断 >> 1
测试传入阶段ID的权限判断 >> 1
测试传入看板ID的权限判断 >> 1

*/

$executionIdList = array('', 0, 1, 101, 131, 161);

$execution = new executionTest();

r($execution->checkPrivTest($executionIdList[0])) && p() && e('0'); // 测试传入空值
r($execution->checkPrivTest($executionIdList[1])) && p() && e('0'); // 测试传入0
r($execution->checkPrivTest($executionIdList[2])) && p() && e('1'); // 测试传入正确的项目ID的权限判断
r($execution->checkPrivTest($executionIdList[3])) && p() && e('1'); // 测试传入迭代ID的权限判断
r($execution->checkPrivTest($executionIdList[4])) && p() && e('1'); // 测试传入阶段ID的权限判断
r($execution->checkPrivTest($executionIdList[5])) && p() && e('1'); // 测试传入看板ID的权限判断
