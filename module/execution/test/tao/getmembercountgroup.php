#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('user')->gen(200);
su('admin');

zdTable('project')->config('execution', true)->gen(30);
zdTable('team')->config('team', true)->gen(60);

/**

title=测试 executionModel->getMemberCountGroup();
timeout=0
cid=1

*/

global $tester;
$executionModel = $tester->loadModel('execution');

r($executionModel->getMemberCountGroup(array()))                           && p()                 && e('0');     // 测试空数据
r(count($executionModel->getMemberCountGroup(array(101,102,103,104,105)))) && p()                 && e('5');     // 测试获取执行的团队成员分组个数
r($executionModel->getMemberCountGroup(array(101,102,103,104,105)))        && p('101:root,teams') && e('101,1'); // 测试获取执行的团队成员个数
r($executionModel->getMemberCountGroup(array(11)))                         && p()                 && e('0');     // 测试获取项目团队成员
