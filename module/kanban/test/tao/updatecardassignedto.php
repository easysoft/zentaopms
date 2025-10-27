#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::updateCardAssignedTo();
cid=0

- 测试步骤1：正常情况下更新指派人，包含有效用户admin和user1 >> 期望过滤后保留有效用户
- 测试步骤2：混合有效和无效用户情况，包含valid和invalid用户 >> 期望过滤掉无效用户只保留有效用户
- 测试步骤3：单个有效用户情况，只包含admin用户 >> 期望保留该有效用户
- 测试步骤4：全部无效用户情况，不包含任何有效用户 >> 期望指派人为空
- 测试步骤5：包含空值和重复用户的情况，处理边界值 >> 期望过滤空值保留有效用户

*/

// 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 管理员登录
su('admin');

// 创建测试实例
$kanbanTest = new kanbanTest();

// 准备用户数组
$users = array(
    'admin' => 'Administrator',
    'user1' => 'User One',
    'user2' => 'User Two',
    'user3' => 'User Three'
);

r($kanbanTest->updateCardAssignedToTest(1, 'admin,user1', $users)) && p('filteredList') && e('admin,user1');
r($kanbanTest->updateCardAssignedToTest(2, 'user2,invalid,user3', $users)) && p('filteredList') && e('user2,user3');
r($kanbanTest->updateCardAssignedToTest(3, 'admin', $users)) && p('filteredList') && e('admin');
r($kanbanTest->updateCardAssignedToTest(4, 'invalid1,invalid2', $users)) && p('filteredList') && e('');
r($kanbanTest->updateCardAssignedToTest(5, 'user1,,admin,', $users)) && p('filteredList') && e('user1,admin');