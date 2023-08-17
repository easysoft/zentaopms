#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('user')->gen(5);
su('admin');

zdTable('project')->config('execution', true)->gen(30);

/**

title=测试 executionModel->getExecutionList();
timeout=0
cid=1

*/

global $tester;
$projectModel = $tester->loadModel('project');

r($projectModel->getExecutionList())                     && p() && e('0');  // 测试空数据
r(count($projectModel->getExecutionList(array(11, 60)))) && p() && e('17'); // 测试获取项目下执行的数量
r($projectModel->getExecutionList(array(1)))             && p() && e('0');  // 测试获取不存在项目下的执行
