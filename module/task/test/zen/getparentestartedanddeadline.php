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
- 步骤6：验证多层级任务路径处理第7条的estStarted属性 @2024-03-01
- 步骤7：验证零时间处理第13条的deadline属性 @2024-04-30

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. 直接插入测试数据
global $tester;
$tester->dao->exec("TRUNCATE TABLE zt_task");
$tester->dao->exec("INSERT INTO zt_task (id, parent, path, project, execution, name, type, status, estStarted, deadline) VALUES
(1, 0, ',1,', 1, 1, '根任务1', 'devel', 'wait', '2024-01-01', '2024-01-31'),
(2, 1, ',1,2,', 1, 1, '子任务1-1', 'devel', 'doing', '0000-00-00', '2024-01-15'),
(3, 1, ',1,3,', 1, 1, '子任务1-2', 'devel', 'done', '2024-01-03', '0000-00-00'),
(4, 0, ',4,', 1, 1, '根任务2', 'test', 'wait', '2024-02-01', '2024-02-28'),
(5, 4, ',4,5,', 1, 1, '子任务2-1', 'test', 'doing', '0000-00-00', '2024-02-15'),
(6, 4, ',4,6,', 1, 1, '子任务2-2', 'test', 'done', '2024-02-03', '0000-00-00'),
(7, 0, ',7,', 1, 1, '根任务3', 'design', 'wait', '2024-03-01', '0000-00-00'),
(8, 7, ',7,8,', 1, 1, '子任务3-1', 'design', 'doing', '0000-00-00', '2024-03-20'),
(9, 7, ',7,9,', 1, 1, '子任务3-2', 'design', 'done', '2024-03-10', '2024-03-18'),
(10, 0, ',10,', 1, 1, '根任务4', 'study', 'wait', '0000-00-00', '2024-04-30'),
(11, 10, ',10,11,', 1, 1, '子任务4-1', 'study', 'doing', '2024-04-01', '0000-00-00'),
(12, 10, ',10,12,', 1, 1, '子任务4-2', 'study', 'done', '2024-04-03', '2024-04-20'),
(13, 0, ',13,', 2, 2, '独立任务1', 'devel', 'wait', '0000-00-00', '2024-04-30'),
(14, 0, ',14,', 2, 2, '独立任务2', 'test', 'doing', '2024-04-01', '0000-00-00'),
(15, 14, ',14,15,', 2, 2, '子任务5-1', 'test', 'done', '2024-04-05', '2024-04-15')");

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskTest = new taskZenTest();

// 5. 强制要求：必须包含至少7个测试步骤
r(count($taskTest->getParentEstStartedAndDeadlineTest(array(1, 4)))) && p() && e('2'); // 步骤1：正常情况返回数量
r(count($taskTest->getParentEstStartedAndDeadlineTest(array()))) && p() && e('0'); // 步骤2：空数组返回数量
r(count($taskTest->getParentEstStartedAndDeadlineTest(array(999, 1000)))) && p() && e('0'); // 步骤3：不存在ID返回数量
r($taskTest->getParentEstStartedAndDeadlineTest(array(1))) && p('1:estStarted') && e('2024-01-01'); // 步骤4：验证父任务1开始时间
r($taskTest->getParentEstStartedAndDeadlineTest(array(4))) && p('4:deadline') && e('2024-02-28'); // 步骤5：验证父任务4截止时间
r($taskTest->getParentEstStartedAndDeadlineTest(array(7))) && p('7:estStarted') && e('2024-03-01'); // 步骤6：验证多层级任务路径处理
r($taskTest->getParentEstStartedAndDeadlineTest(array(13))) && p('13:deadline') && e('2024-04-30'); // 步骤7：验证零时间处理