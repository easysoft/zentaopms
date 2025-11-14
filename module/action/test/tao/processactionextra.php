#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processActionExtra();
timeout=0
cid=14958

- 步骤1：正常情况生成链接（bug转任务场景）属性result @bug_to_task
- 步骤2：禁用链接时生成纯文本属性result @no_link
- 步骤3：对象不存在时不变属性result @no_change
- 步骤4：onlyBody模式处理属性result @onlybody_mode
- 步骤5：bug转任务场景属性result @bug_to_task

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$taskTable = zenData('task');
$taskTable->id->range('1-5');
$taskTable->name->range('测试任务1,测试任务2,测试任务3,测试任务4,测试任务5');
$taskTable->type->range('devel,test,design,study,misc');
$taskTable->status->range('wait,doing,done,pause,cancel');
$taskTable->project->range('1-3');
$taskTable->execution->range('1-3');
$taskTable->gen(5);

$bugTable = zenData('bug');
$bugTable->id->range('1-5');
$bugTable->title->range('Bug标题1,Bug标题2,Bug标题3,Bug标题4,Bug标题5');
$bugTable->status->range('active,resolved,closed');
$bugTable->product->range('1-3');
$bugTable->gen(5);

$actionTable = zenData('action');
$actionTable->id->range('1-10');
$actionTable->objectType->range('bug{5},task{5}');
$actionTable->objectID->range('1-5');
$actionTable->action->range('converttotask,opened,edited,closed,activated');
$actionTable->extra->range('1,2,3,4,5');
$actionTable->project->range('1-3');
$actionTable->execution->range('1-3');
$actionTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($actionTest->processActionExtraTest('zt_task', 1, 'name', 'task', 'view', false, true)) && p('result') && e('bug_to_task'); // 步骤1：正常情况生成链接（bug转任务场景）
r($actionTest->processActionExtraTest('zt_task', 2, 'name', 'task', 'view', false, false)) && p('result') && e('no_link'); // 步骤2：禁用链接时生成纯文本
r($actionTest->processActionExtraTest('zt_task', 999, 'name', 'task', 'view', false, true)) && p('result') && e('no_change'); // 步骤3：对象不存在时不变
r($actionTest->processActionExtraTest('zt_task', 3, 'name', 'task', 'view', true, true)) && p('result') && e('onlybody_mode'); // 步骤4：onlyBody模式处理
r($actionTest->processActionExtraTest('zt_bug', 1, 'title', 'task', 'view', false, true)) && p('result') && e('bug_to_task'); // 步骤5：bug转任务场景