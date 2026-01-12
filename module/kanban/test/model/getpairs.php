#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::getPairs();
timeout=0
cid=16935

- 管理员可以看到所有5个看板 @5
- user3只能看到2个有权限的看板 @2
- 返回数组格式 @array
- 验证看板2的名称正确属性2 @看板2
- user1看板访问权限受限 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 数据准备
zenData('user')->gen(5);
zenData('kanbanspace')->loadYaml('kanbanspace')->gen(5);
zenData('kanban')->loadYaml('kanban')->gen(5);

// 创建测试实例
$kanbanTest = new kanbanTest();

// 测试步骤1：管理员用户查看所有看板数量
su('admin');
r(count($kanbanTest->getPairsTest())) && p() && e('5'); // 管理员可以看到所有5个看板

// 测试步骤2：普通用户查看可见看板数量
su('user3');
r(count($kanbanTest->getPairsTest())) && p() && e('2'); // user3只能看到2个有权限的看板

// 测试步骤3：检查返回数据格式为键值对
su('admin');
r(gettype($kanbanTest->getPairsTest())) && p() && e('array'); // 返回数组格式

// 测试步骤4：验证看板名称获取正确性
su('user3');
r($kanbanTest->getPairsTest()) && p('2') && e('看板2'); // 验证看板2的名称正确

// 测试步骤5：测试权限受限用户的看板访问
su('user1');
r(count($kanbanTest->getPairsTest())) && p() && e('1'); // user1看板访问权限受限
