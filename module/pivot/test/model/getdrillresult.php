#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getDrillResult();
timeout=0
cid=17380

- 步骤1：正常情况属性status @success
- 步骤2：包含过滤器属性status @success
- 步骤3：空过滤器属性status @success
- 步骤4：限制记录数属性status @success
- 步骤5：无效对象属性status @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-10');
$task->name->range('任务{1-10}');
$task->status->range('wait{3},doing{3},done{4}');
$task->project->range('1-3');
$task->openedBy->range('admin,user1,user2');
$task->gen(10);

$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目{1-3}');
$project->status->range('doing{2},closed{1}');
$project->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($pivotTest->getDrillResultTest('task', 'WHERE t1.id > 0', array(), array(), true, 10)) && p('status') && e('success'); // 步骤1：正常情况
r($pivotTest->getDrillResultTest('task', 'WHERE t1.status = "wait"', array('status' => 'wait'), array(), false, 10)) && p('status') && e('success'); // 步骤2：包含过滤器
r($pivotTest->getDrillResultTest('task', 'WHERE t1.id > 0', array(), array(), true, 10)) && p('status') && e('success'); // 步骤3：空过滤器
r($pivotTest->getDrillResultTest('task', 'WHERE t1.id > 0', array(), array(), true, 5)) && p('status') && e('success'); // 步骤4：限制记录数
r($pivotTest->getDrillResultTest('nonexistent', 'WHERE t1.id > 0', array(), array(), true, 10)) && p('status') && e('fail'); // 步骤5：无效对象