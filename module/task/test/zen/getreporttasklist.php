#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getReportTaskList();
timeout=0
cid=18934

- 步骤1：正常情况第7条的name属性 @普通任务1
- 步骤2：按搜索条件第1条的name属性 @搜索任务1
- 步骤3：按模块获取第3条的module属性 @2
- 步骤4：按产品获取第5条的product属性 @1
- 步骤5：非多项目执行属性multiple_processed @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->loadYaml('project_getreporttasklist', false, 2)->gen(10);

$taskTable = zenData('task');
$taskTable->loadYaml('task_getreporttasklist', false, 2)->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试用的execution对象
$execution1 = new stdClass();
$execution1->id = 1;
$execution1->name = '项目1';
$execution1->project = 1;
$execution1->multiple = 1;
$execution1->type = 'sprint';

$execution2 = new stdClass();
$execution2->id = 2;
$execution2->name = '项目2';
$execution2->project = 2;
$execution2->multiple = 0;
$execution2->type = 'stage';

// 步骤1：正常execution对象，无browseType参数
r($taskZenTest->getReportTaskListTest($execution1)) && p('7:name') && e('普通任务1'); // 步骤1：正常情况

// 步骤2：browseType为'bysearch'，param为查询ID
r($taskZenTest->getReportTaskListTest($execution1, 'bysearch', 1)) && p('1:name') && e('搜索任务1'); // 步骤2：按搜索条件

// 步骤3：browseType为'bymodule'，param为模块ID
r($taskZenTest->getReportTaskListTest($execution1, 'bymodule', 2)) && p('3:module') && e('2'); // 步骤3：按模块获取

// 步骤4：browseType为'byproduct'，param为产品ID
r($taskZenTest->getReportTaskListTest($execution1, 'byproduct', 1)) && p('5:product') && e('1'); // 步骤4：按产品获取

// 步骤5：execution对象multiple为false
r($taskZenTest->getReportTaskListTest($execution2, '', 0)) && p('multiple_processed') && e('1'); // 步骤5：非多项目执行