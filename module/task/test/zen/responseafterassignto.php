#!/usr/bin/env php
<?php

/**

title=测试 taskZen::responseAfterAssignTo();
timeout=0
cid=18941

- 步骤1：正常分配任务返回成功响应
 - 属性result @success
 - 属性message @保存成功
 - 属性closeModal @1
- 步骤2：任务看板来源的模态窗口响应
 - 属性result @success
 - 属性message @保存成功
 - 属性callback @refreshKanban()
- 步骤3：看板类型执行的模态窗口响应
 - 属性result @success
 - 属性message @保存成功
 - 属性callback @refreshKanban()
- 步骤4：普通模态窗口的响应
 - 属性result @success
 - 属性message @保存成功
 - 属性closeModal @1
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
$task->execution->range('1-3,8{2}');
$task->name->range('任务{1-10}');
$task->type->range('devel,design,test,study,discuss,ui,affair,misc');
$task->status->range('wait,doing,done,pause,cancel,closed');
$task->assignedTo->range('admin,user1,user2,user3,closed');
$task->gen(10);

$project = zenData('project');
$project->id->range('1-8');
$project->name->range('项目{1-5},执行{1-3}');
$project->type->range('project{3},sprint{2},stage,waterfall,kanban');
$project->status->range('wait,doing,done');
$project->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskZenTest->responseAfterAssignToTest(1, '')) && p('result,message,closeModal') && e('success,保存成功,1'); // 步骤1：正常分配任务返回成功响应
r($taskZenTest->responseAfterAssignToTest(2, 'taskkanban')) && p('result,message,callback') && e('success,保存成功,refreshKanban()'); // 步骤2：任务看板来源的模态窗口响应
r($taskZenTest->responseAfterAssignToTest(9, 'modal')) && p('result,message,callback') && e('success,保存成功,refreshKanban()'); // 步骤3：看板类型执行的模态窗口响应
r($taskZenTest->responseAfterAssignToTest(3, 'modal')) && p('result,message,closeModal') && e('success,保存成功,1'); // 步骤4：普通模态窗口的响应
r($taskZenTest->responseAfterAssignToTest(999, '')) && p('result,message,closeModal') && e('success,保存成功,1'); // 步骤5：无效任务ID的边界处理情况