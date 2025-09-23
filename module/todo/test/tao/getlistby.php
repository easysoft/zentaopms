#!/usr/bin/env php
<?php

/**

title=测试 todoTao::getListBy();
timeout=0
cid=0

- 步骤1：正常获取today类型待办列表 @1
- 步骤2：获取指派给其他人的待办 @0
- 步骤3：获取user1的待办 @1
- 步骤4：按日期范围获取待办 @1
- 步骤5：获取未完成状态待办 @1
- 步骤6：限制返回数量为1 @1
- 步骤7：测试其他用户的待办列表 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

// 2. zendata数据准备
zenData('todo')->loadYaml('todo', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$todoTest = new todoTest();

// 5. 测试步骤
r(count($todoTest->getListByTest('today', 'admin', 'all', '2025-08-24', '2025-08-24', 0, 'date_desc'))) && p() && e('1'); // 步骤1：正常获取today类型待办列表
r(count($todoTest->getListByTest('assignedtoother', 'admin', 'all', '', '', 0, 'date_desc'))) && p() && e('0'); // 步骤2：获取指派给其他人的待办
r(count($todoTest->getListByTest('today', 'user1', 'all', '2025-08-25', '2025-08-25', 0, 'date_desc'))) && p() && e('1'); // 步骤3：获取user1的待办
r(count($todoTest->getListByTest('today', 'admin', 'all', '2025-08-01', '2025-09-30', 0, 'date_desc'))) && p() && e('1'); // 步骤4：按日期范围获取待办
r(count($todoTest->getListByTest('today', 'user1', 'undone', '2025-08-25', '2025-08-25', 0, 'date_desc'))) && p() && e('1'); // 步骤5：获取未完成状态待办
r(count($todoTest->getListByTest('today', 'admin', 'all', '2025-08-01', '2025-09-30', 1, 'date_desc'))) && p() && e('1'); // 步骤6：限制返回数量为1
r(count($todoTest->getListByTest('today', 'user2', 'all', '2025-08-26', '2025-08-26', 0, 'id_asc'))) && p() && e('1'); // 步骤7：测试其他用户的待办列表