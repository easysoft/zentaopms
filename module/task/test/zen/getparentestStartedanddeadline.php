#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getParentEstStartedAndDeadline();
timeout=0
cid=0

- 步骤1：正常情况返回数量 @2
- 步骤2：空数组返回数量 @0
- 步骤3：不存在ID返回数量 @0
- 步骤4：验证父任务1开始时间第1条的estStarted属性 @2024-01-01
- 步骤5：验证父任务4截止时间第4条的deadline属性 @2024-02-28

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. 直接插入测试数据
global $tester;
$tester->dao->exec("TRUNCATE TABLE zt_task");
$tester->dao->exec("INSERT INTO zt_task (id, parent, path, project, execution, name, type, status, estStarted, deadline) VALUES
(1, 0, ',1,', 1, 1, '父任务1', 'devel', 'wait', '2024-01-01', '2024-01-31'),
(2, 1, ',1,2,', 1, 1, '子任务1-1', 'devel', 'doing', '0000-00-00', '2024-01-15'),
(3, 1, ',1,3,', 1, 1, '子任务1-2', 'devel', 'done', '2024-01-03', '0000-00-00'),
(4, 0, ',4,', 1, 1, '父任务2', 'test', 'wait', '2024-02-01', '2024-02-28'),
(5, 4, ',4,5,', 1, 1, '子任务2-1', 'test', 'doing', '0000-00-00', '2024-02-15'),
(6, 4, ',4,6,', 1, 1, '子任务2-2', 'test', 'done', '2024-02-03', '0000-00-00')");

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskTest = new taskZenTest();

// 5. 必须包含至少5个测试步骤
r(count($taskTest->getParentEstStartedAndDeadlineTest(array(1, 4)))) && p() && e('2'); // 步骤1：正常情况返回数量
r(count($taskTest->getParentEstStartedAndDeadlineTest(array()))) && p() && e('0'); // 步骤2：空数组返回数量
r(count($taskTest->getParentEstStartedAndDeadlineTest(array(999, 1000)))) && p() && e('0'); // 步骤3：不存在ID返回数量
r($taskTest->getParentEstStartedAndDeadlineTest(array(1))) && p('1:estStarted') && e('2024-01-01'); // 步骤4：验证父任务1开始时间
r($taskTest->getParentEstStartedAndDeadlineTest(array(4))) && p('4:deadline') && e('2024-02-28'); // 步骤5：验证父任务4截止时间