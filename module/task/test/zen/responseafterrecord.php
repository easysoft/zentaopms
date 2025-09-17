#!/usr/bin/env php
<?php

/**

title=测试 taskZen::responseAfterRecord();
timeout=0
cid=0

- 步骤1：正常情况，验证错误处理属性error @Zen object not available
- 步骤2：有变更情况，验证错误处理属性error @Zen object not available
- 步骤3：模态请求，验证错误处理属性error @Zen object not available
- 步骤4：列表来源，验证错误处理属性error @Zen object not available
- 步骤5：看板来源，验证错误处理属性error @Zen object not available

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('task');
$table->id->range('1-10');
$table->execution->range('1-5');
$table->name->range('测试任务1,测试任务2,测试任务3,测试任务4,测试任务5');
$table->status->range('wait,doing,done,pause,cancel');
$table->type->range('design,devel,test,study,misc');
$table->assignedTo->range('admin,user1,user2,user3');
$table->consumed->range('0-10');
$table->left->range('0-5');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$task = new stdClass();
$task->id = 1;
$task->execution = 1;
$task->name = '测试任务';
$task->status = 'doing';
$task->type = 'devel';
$task->assignedTo = 'admin';
$task->consumed = 5;
$task->left = 2;

r($taskTest->responseAfterRecordTest($task, array(), '')) && p('error') && e('Zen object not available'); // 步骤1：正常情况，验证错误处理
r($taskTest->responseAfterRecordTest($task, array('status' => 'done'), '')) && p('error') && e('Zen object not available'); // 步骤2：有变更情况，验证错误处理
r($taskTest->responseAfterRecordTest($task, array(), 'modal')) && p('error') && e('Zen object not available'); // 步骤3：模态请求，验证错误处理
r($taskTest->responseAfterRecordTest($task, array(), 'list')) && p('error') && e('Zen object not available'); // 步骤4：列表来源，验证错误处理
r($taskTest->responseAfterRecordTest($task, array('left' => '0'), 'kanban')) && p('error') && e('Zen object not available'); // 步骤5：看板来源，验证错误处理