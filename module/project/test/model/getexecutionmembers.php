#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('project')->gen(7);
zdTable('team')->config('team')->gen(4);
zdTable('user')->config('user')->gen(3);

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
