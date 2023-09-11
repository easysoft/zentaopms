#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(300);
su('admin');

zdTable('project')->config('execution')->gen(30);
zdTable('team')->config('team')->gen(30);

/**

title=测试executionModel->updateProjectUsers();
timeout=0
cid=1

*/

$newProjectIdList = array(11, 60, 100);
$executionIdList  = array(101, 106, 124);

global $tester;
$tester->loadModel('execution');
r($tester->execution->updateProjectUsers($executionIdList[1], $newProjectIdList[0])) && p('user9') && e('user9'); // 测试阶段更新到敏捷项目下
r($tester->execution->updateProjectUsers($executionIdList[2], $newProjectIdList[0])) && p('dev7')  && e('dev7');  // 测试看板更新到敏捷项目下
r($tester->execution->updateProjectUsers($executionIdList[0], $newProjectIdList[1])) && p('user4') && e('user4'); // 测试迭代更新到瀑布项目下
r($tester->execution->updateProjectUsers($executionIdList[2], $newProjectIdList[1])) && p('dev7')  && e('dev7');  // 测试看板更新到瀑布项目下
r($tester->execution->updateProjectUsers($executionIdList[0], $newProjectIdList[2])) && p('user4') && e('user4'); // 测试迭代更新到看板项目下
r($tester->execution->updateProjectUsers($executionIdList[1], $newProjectIdList[2])) && p('user9') && e('user9'); // 测试阶段更新到看板项目下
