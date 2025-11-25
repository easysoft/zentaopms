#!/usr/bin/env php
<?php

/**

title=测试 transferZen::printRow();
timeout=0
cid=0

- 步骤1：测试有ID的普通对象生成表格行 @1
- 步骤2：测试无ID的新建对象生成表格行 @1
- 步骤3：测试task模块子任务对象生成表格行 @1
- 步骤4：测试带trClass的表格行 @1
- 步骤5：测试actionModule模块生成带删除按钮的表格行 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$transferTest = new transferZenTest();

// 4. 准备测试数据
$fields = array(
    'name' => array('control' => 'input', 'values' => array()),
    'status' => array('control' => 'select', 'values' => array('wait' => '未开始', 'doing' => '进行中'))
);

// 有ID的对象
$objectWithId = new stdClass();
$objectWithId->id = 101;
$objectWithId->name = 'Task Name';
$objectWithId->status = 'wait';

// 无ID的新建对象
$objectNewTask = new stdClass();
$objectNewTask->name = 'New Task';
$objectNewTask->status = 'doing';

// 子任务对象（task模块特殊标识）
$objectChildTask = new stdClass();
$objectChildTask->name = '>Child Task';
$objectChildTask->status = 'wait';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $transferTest->printRowTest('task', 1, $fields, $objectWithId, '', 1);
$result2 = $transferTest->printRowTest('task', 1, $fields, $objectNewTask, '', 1);
$result3 = $transferTest->printRowTest('task', 1, $fields, $objectChildTask, '', 1);
$result4 = $transferTest->printRowTest('task', 5, $fields, $objectWithId, 'showmore', 1);
$result5 = $transferTest->printRowTest('story', 1, $fields, $objectWithId, '', 1);

r(strpos($result1, '101') !== false && strpos($result1, "value='101'") !== false) && p() && e('1'); // 步骤1：测试有ID的普通对象生成表格行
r(strpos($result2, '新建') !== false && strpos($result2, '2') !== false) && p() && e('1'); // 步骤2：测试无ID的新建对象生成表格行
r(strpos($result3, '子任务') !== false) && p() && e('1'); // 步骤3：测试task模块子任务对象生成表格行
r(strpos($result4, 'showmore') !== false) && p() && e('1'); // 步骤4：测试带trClass的表格行
r(strpos($result5, 'icon-close') !== false) && p() && e('1'); // 步骤5：测试actionModule模块生成带删除按钮的表格行