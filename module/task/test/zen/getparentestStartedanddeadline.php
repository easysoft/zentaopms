#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getParentEstStartedAndDeadline();
timeout=0
cid=0

- 步骤1：正常父任务ID列表数量 @2
- 步骤2：空父任务ID列表数量 @0
- 步骤3：不存在的父任务ID数量 @0
- 步骤4：混合有效和无效ID数量 @1
- 步骤5：验证返回的开始时间第1条的estStarted属性 @2024-01-01

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. 直接使用SQL插入测试数据
global $tester;
$tester->dao->exec("TRUNCATE TABLE zt_task");
$tester->dao->exec("INSERT INTO zt_task (id, parent, path, project, execution, name, type, status, estStarted, deadline) VALUES
(1, 0, ',1,', 1, 1, '父任务1', 'devel', 'wait', '2024-01-01', '2024-02-01'),
(2, 1, ',1,2,', 1, 1, '子任务1', 'devel', 'doing', '2024-01-02', '2024-02-02'),
(3, 1, ',1,3,', 1, 1, '子任务2', 'devel', 'doing', '2024-01-03', '2024-02-03'),
(4, 0, ',4,', 1, 1, '父任务2', 'test', 'wait', '2024-01-04', '2024-02-04'),
(5, 4, ',4,5,', 1, 1, '子任务3', 'test', 'done', '2024-01-05', '2024-02-05')");

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskZenTest = new taskZenTest();

// 5. 测试步骤：必须包含至少5个测试步骤
r(count($taskZenTest->getParentEstStartedAndDeadlineTest(array(1, 4)))) && p() && e('2'); // 步骤1：正常父任务ID列表数量
r(count($taskZenTest->getParentEstStartedAndDeadlineTest(array()))) && p() && e('0'); // 步骤2：空父任务ID列表数量
r(count($taskZenTest->getParentEstStartedAndDeadlineTest(array(999, 1000)))) && p() && e('0'); // 步骤3：不存在的父任务ID数量
r(count($taskZenTest->getParentEstStartedAndDeadlineTest(array(1, 999)))) && p() && e('1'); // 步骤4：混合有效和无效ID数量
r($taskZenTest->getParentEstStartedAndDeadlineTest(array(1))) && p('1:estStarted') && e('2024-01-01'); // 步骤5：验证返回的开始时间