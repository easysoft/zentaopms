#!/usr/bin/env php
<?php

/**

title=测试 projectZen::removeAssociatedExecutions();
timeout=0
cid=17961

- 步骤1：正常删除单个执行 @success
- 步骤2：正常删除多个执行 @success
- 步骤3：删除空执行列表 @success
- 步骤4：删除不存在的执行 @success
- 步骤5：删除包含多个执行的列表 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备
$execution = zenData('project');
$execution->id->range('101-120');
$execution->type->range('sprint{10}');
$execution->name->range('执行1,执行2,执行3,执行4,执行5,执行6,执行7,执行8,执行9,执行10');
$execution->status->range('wait{3},doing{4},closed{3}');
$execution->parent->range('1{5},2{5}');
$execution->gen(10);

$action = zenData('action');
$action->id->range('1-100');
$action->objectType->range('execution{50}');
$action->objectID->range('101-120');
$action->action->range('opened{25},started{15},deleted{10}');
$action->gen(50);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$projectzenTest = new projectzenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($projectzenTest->removeAssociatedExecutionsTest(array(101 => 101))) && p() && e('success'); // 步骤1：正常删除单个执行
r($projectzenTest->removeAssociatedExecutionsTest(array(102 => 102, 103 => 103))) && p() && e('success'); // 步骤2：正常删除多个执行
r($projectzenTest->removeAssociatedExecutionsTest(array())) && p() && e('success'); // 步骤3：删除空执行列表
r($projectzenTest->removeAssociatedExecutionsTest(array(999 => 999))) && p() && e('success'); // 步骤4：删除不存在的执行
r($projectzenTest->removeAssociatedExecutionsTest(array(104 => 104, 105 => 105))) && p() && e('success'); // 步骤5：删除包含多个执行的列表