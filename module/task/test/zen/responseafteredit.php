#!/usr/bin/env php
<?php

/**

title=测试 taskZen::responseAfterEdit();
timeout=0
cid=18946

- 步骤1：正常编辑任务返回成功响应
 - 属性result @success
 - 属性message @保存成功
 - 属性closeModal @1
- 步骤2：任务看板来源的正常响应
 - 属性result @success
 - 属性message @保存成功
 - 属性closeModal @1
- 步骤3：来自Bug的任务状态变更响应
 - 属性result @success
 - 属性message @保存成功
- 步骤4：一般字段变更的正常响应
 - 属性result @success
 - 属性message @保存成功
- 步骤5：无效任务ID的边界处理情况
 - 属性result @success
 - 属性message @保存成功
 - 属性closeModal @1

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
$task->fromBug->range('0{7},1,2,3');
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
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskZenTest->responseAfterEditTest(1, '', array())) && p('result,message,closeModal') && e('success,保存成功,1'); // 步骤1：正常编辑任务返回成功响应
r($taskZenTest->responseAfterEditTest(2, 'taskkanban', array())) && p('result,message,closeModal') && e('success,保存成功,1'); // 步骤2：任务看板来源的正常响应
r($taskZenTest->responseAfterEditTest(8, '', array(array('field' => 'status', 'old' => 'wait', 'new' => 'doing')))) && p('result,message') && e('success,保存成功'); // 步骤3：来自Bug的任务状态变更响应
r($taskZenTest->responseAfterEditTest(3, '', array(array('field' => 'name', 'old' => '旧名称', 'new' => '新名称')))) && p('result,message') && e('success,保存成功'); // 步骤4：一般字段变更的正常响应
r($taskZenTest->responseAfterEditTest(999, '', array())) && p('result,message,closeModal') && e('success,保存成功,1'); // 步骤5：无效任务ID的边界处理情况