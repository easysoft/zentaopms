#!/usr/bin/env php
<?php

/**

title=测试 taskZen::responseAfterBatchEdit();
timeout=0
cid=18943

- 步骤1：空变更数组
 - 属性result @success
 - 属性message @保存成功
- 步骤2：无来源Bug任务状态变更
 - 属性result @success
 - 属性message @保存成功
- 步骤3：有来源Bug任务状态变更属性result @success
- 步骤4：有来源Bug任务非状态变更
 - 属性result @success
 - 属性message @保存成功
- 步骤5：多任务混合变更属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1-3');
$task->execution->range('1-3');
$task->name->range('任务{1-10}');
$task->type->range('devel,design,test,study,discuss,ui,affair,misc');
$task->status->range('wait,doing,done,pause,cancel,closed');
$task->assignedTo->range('admin,user1,user2,user3,closed');
$task->fromBug->range('0{6},1,2,3,0');
$task->gen(10);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目{1-5}');
$project->type->range('project');
$project->status->range('wait,doing,done');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskzenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($taskZenTest->responseAfterBatchEditTest(array())) && p('result,message') && e('success,保存成功'); // 步骤1：空变更数组
r($taskZenTest->responseAfterBatchEditTest(array(1 => array(array('field' => 'status', 'old' => 'wait', 'new' => 'doing'))))) && p('result,message') && e('success,保存成功'); // 步骤2：无来源Bug任务状态变更
r($taskZenTest->responseAfterBatchEditTest(array(7 => array(array('field' => 'status', 'old' => 'wait', 'new' => 'doing'))))) && p('result') && e('success'); // 步骤3：有来源Bug任务状态变更
r($taskZenTest->responseAfterBatchEditTest(array(7 => array(array('field' => 'name', 'old' => '任务7', 'new' => '更新任务7'))))) && p('result,message') && e('success,保存成功'); // 步骤4：有来源Bug任务非状态变更
r($taskZenTest->responseAfterBatchEditTest(array(1 => array(array('field' => 'assignedTo', 'old' => 'admin', 'new' => 'user1')), 7 => array(array('field' => 'status', 'old' => 'wait', 'new' => 'doing'))))) && p('result') && e('success'); // 步骤5：多任务混合变更