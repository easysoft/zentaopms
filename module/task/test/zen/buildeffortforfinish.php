#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildEffortForFinish();
timeout=0
cid=0

- 步骤1：正常消耗时间
 - 属性consumed @5.5
 - 属性left @0
 - 属性account @admin
- 步骤2：边界值0消耗
 - 属性consumed @0
 - 属性left @0
 - 属性account @admin
- 步骤3：负数消耗时间 @"总计消耗"必须大于之前消耗
- 步骤4：带评论输入
 - 属性consumed @3.5
 - 属性work @Task completed successfully
- 步骤5：空字符串消耗时间
 - 属性consumed @0
 - 属性left @0
 - 属性account @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. 不需要复杂的zendata数据，直接使用测试对象

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 准备测试数据
$oldTask = new stdclass();
$oldTask->id = 1;
$oldTask->name = 'Test Task';
$oldTask->status = 'doing';

$task = new stdclass();
$task->finishedDate = '2024-01-15 10:30:00';
$task->work = 'Initial work description';

// 5. 强制要求：必须包含至少5个测试步骤
r($taskTest->buildEffortForFinishTest($oldTask, $task, '5.5', '')) && p('consumed,left,account') && e('5.5,0,admin'); // 步骤1：正常消耗时间
r($taskTest->buildEffortForFinishTest($oldTask, $task, '0', '')) && p('consumed,left,account') && e('0,0,admin'); // 步骤2：边界值0消耗
r($taskTest->buildEffortForFinishTest($oldTask, $task, '-1', '')) && p('0') && e('"总计消耗"必须大于之前消耗'); // 步骤3：负数消耗时间
r($taskTest->buildEffortForFinishTest($oldTask, $task, '3.5', 'Task completed successfully')) && p('consumed,work') && e('3.5,Task completed successfully'); // 步骤4：带评论输入
r($taskTest->buildEffortForFinishTest($oldTask, $task, '', '')) && p('consumed,left,account') && e('0,0,admin'); // 步骤5：空字符串消耗时间