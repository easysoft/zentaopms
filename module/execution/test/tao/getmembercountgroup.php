#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(200);
su('admin');

zenData('project')->loadYaml('execution', true)->gen(30);
zenData('team')->loadYaml('team', true)->gen(60);

/**

title=测试 executionModel->getMemberCountGroup();
timeout=0
cid=16389

- 测试空数据 @0
- 测试获取执行的团队成员分组个数 @5
- 测试获取执行的团队成员个数
 - 第101条的root属性 @101
 - 第101条的teams属性 @1
- 测试获取项目团队成员 @0

*/

global $tester;
$executionModel = $tester->loadModel('execution');

r($executionModel->getMemberCountGroup(array()))                           && p()                 && e('0');     // 测试空数据
r(count($executionModel->getMemberCountGroup(array(101,102,103,104,105)))) && p()                 && e('5');     // 测试获取执行的团队成员分组个数
r($executionModel->getMemberCountGroup(array(101,102,103,104,105)))        && p('101:root,teams') && e('101,1'); // 测试获取执行的团队成员个数
r($executionModel->getMemberCountGroup(array(11)))                         && p()                 && e('0');     // 测试获取项目团队成员