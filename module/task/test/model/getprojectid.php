#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('project')->config('project', true)->gen(4);

/**

title=taskModel->getProjectID();
timeout=0
cid=1

*/

$executionIdList = array(0, 1, 2, 3, 4, 5);

$taskModel = $tester->loadModel('task');
r($taskModel->getProjectID($executionIdList[0])) && p() && e('0'); // 测试传入空值
r($taskModel->getProjectID($executionIdList[1])) && p() && e('0'); // 测试获取项目的项目ID
r($taskModel->getProjectID($executionIdList[2])) && p() && e('1'); // 测试获取迭代的项目ID
r($taskModel->getProjectID($executionIdList[3])) && p() && e('1'); // 测试获取阶段的项目ID
r($taskModel->getProjectID($executionIdList[4])) && p() && e('1'); // 测试获取看板的项目ID
r($taskModel->getProjectID($executionIdList[5])) && p() && e('0'); // 测试获取不存在的执行的项目ID
