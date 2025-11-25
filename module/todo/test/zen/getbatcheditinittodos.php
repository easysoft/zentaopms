#!/usr/bin/env php
<?php

/**

title=测试 todoZen::getBatchEditInitTodos();
timeout=0
cid=19300

- 步骤1：空待办ID列表情况，返回数组长度为2 @2
- 步骤2：有效待办ID列表，返回3个待办 @3
- 步骤3：无效待办ID列表，返回0个待办 @0
- 步骤4：不同类型参数，返回数组长度为2 @2
- 步骤5：不同状态参数，返回数组长度为2 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（使用简化的数据准备）
// 为了避免数据库格式问题，我们主要依靠模拟数据进行测试

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $todoTest->getBatchEditInitTodosTest(array(), 'today', 'admin', 'all');
r(count($result1)) && p() && e('2'); // 步骤1：空待办ID列表情况，返回数组长度为2

$result2 = $todoTest->getBatchEditInitTodosTest(array(1, 2, 3), 'today', 'admin', 'all');
r(count($result2[0])) && p() && e('3'); // 步骤2：有效待办ID列表，返回3个待办

$result3 = $todoTest->getBatchEditInitTodosTest(array(999, 1000), 'today', 'admin', 'all');
r(count($result3[0])) && p() && e('0'); // 步骤3：无效待办ID列表，返回0个待办

$result4 = $todoTest->getBatchEditInitTodosTest(array(1, 2, 3, 4, 5), 'assignedTo', 'admin', 'all');
r(count($result4)) && p() && e('2'); // 步骤4：不同类型参数，返回数组长度为2

$result5 = $todoTest->getBatchEditInitTodosTest(array(1, 2, 3, 4, 5), 'today', 'admin', 'wait');
r(count($result5)) && p() && e('2'); // 步骤5：不同状态参数，返回数组长度为2