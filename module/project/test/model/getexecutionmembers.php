#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('project')->gen(7);
zenData('team')->loadYaml('team')->gen(4);
zenData('user')->loadYaml('user')->gen(3);

/**

title=测试 projectModel->getExecutionMembers();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

$executionIdList  = array(2, 4, 5, 7);
$executionMembers = $tester->project->getExecutionMembers('admin', $executionIdList);

r(count($executionMembers)) && p() && e('2');       // 获取admin的执行键值对总数
r($executionMembers[2])     && p() && e('迭代1-2'); // 获取admin的执行键值对
