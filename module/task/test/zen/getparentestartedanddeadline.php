#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getParentEstStartedAndDeadline();
timeout=0
cid=18933

- 步骤1：正常情况，根任务查询开始时间第1条的estStarted属性 @2024-01-01
- 步骤2：边界值，空数组输入 @0
- 步骤3：异常输入，不存在的ID @0
- 步骤4：复杂路径，孙任务从自身路径查找开始时间第5条的estStarted属性 @2024-01-10
- 步骤5：子任务查询截止时间第3条的deadline属性 @2024-01-20
- 步骤6：独立任务查询开始时间第6条的estStarted属性 @2024-03-01
- 步骤7：零日期处理，从父任务路径查找有效截止时间第7条的deadline属性 @2024-02-28

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备
zenData('task')->gen(0);

global $tester;
$tester->dao->exec("
    INSERT INTO " . TABLE_TASK . "
    (id, parent, project, execution, name, path, estStarted, deadline, status, deleted) VALUES
    (1, 0, 1, 1, '父任务1', ',1,', '2024-01-01', '2024-01-31', 'wait', '0'),
    (2, 0, 1, 1, '父任务2', ',2,', '0000-00-00', '2024-02-28', 'wait', '0'),
    (3, 1, 1, 1, '子任务1-1', ',1,3,', '0000-00-00', '2024-01-20', 'wait', '0'),
    (4, 1, 1, 1, '子任务1-2', ',1,4,', '2024-01-15', '0000-00-00', 'doing', '0'),
    (5, 3, 1, 1, '孙任务1-1-1', ',1,3,5,', '2024-01-10', '2024-01-25', 'wait', '0'),
    (6, 0, 1, 1, '独立任务', ',6,', '2024-03-01', '2024-03-31', 'wait', '0'),
    (7, 2, 1, 1, '子任务2-1', ',2,7,', '2024-02-01', '0000-00-00', 'wait', '0')
");

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->getParentEstStartedAndDeadlineTest(array(1))) && p('1:estStarted') && e('2024-01-01'); // 步骤1：正常情况，根任务查询开始时间
r($taskTest->getParentEstStartedAndDeadlineTest(array())) && p() && e('0'); // 步骤2：边界值，空数组输入
r($taskTest->getParentEstStartedAndDeadlineTest(array(999, 1000))) && p() && e('0'); // 步骤3：异常输入，不存在的ID
r($taskTest->getParentEstStartedAndDeadlineTest(array(5))) && p('5:estStarted') && e('2024-01-10'); // 步骤4：复杂路径，孙任务从自身路径查找开始时间
r($taskTest->getParentEstStartedAndDeadlineTest(array(3))) && p('3:deadline') && e('2024-01-20'); // 步骤5：子任务查询截止时间
r($taskTest->getParentEstStartedAndDeadlineTest(array(6))) && p('6:estStarted') && e('2024-03-01'); // 步骤6：独立任务查询开始时间
r($taskTest->getParentEstStartedAndDeadlineTest(array(7))) && p('7:deadline') && e('2024-02-28'); // 步骤7：零日期处理，从父任务路径查找有效截止时间