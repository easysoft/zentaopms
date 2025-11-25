#!/usr/bin/env php
<?php

/**

title=测试 taskZen::responseAfterbatchCreate();
timeout=0
cid=18942

- 步骤1：正常批量创建任务成功情况
 - 属性result @success
 - 属性message @保存成功
- 步骤2：API模式下的批量创建属性result @success
- 步骤3：模态框模式下的批量创建
 - 属性result @success
 - 属性closeModal @1
 - 属性callback @loadCurrentPage()
- 步骤4：my标签页下的批量创建
 - 属性result @success
 - 属性load @my-work-mode=task
- 步骤5：project标签页多执行模式
 - 属性result @success
 - 属性load @project-execution-browseType=all&projectID=2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->type->range('project,sprint,kanban');
$project->multiple->range('0,1');
$project->status->range('wait,doing');
$project->gen(5);

$task = zenData('task');
$task->id->range('1-20');
$task->name->range('任务{1-20}');
$task->execution->range('1-5');
$task->status->range('wait,doing,done');
$task->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 创建测试数据对象
$executionObj = new stdClass();
$executionObj->id = 1;
$executionObj->multiple = 0;
$executionObj->project = 1;

$executionMultipleObj = new stdClass();
$executionMultipleObj->id = 2;
$executionMultipleObj->multiple = 1;
$executionMultipleObj->project = 2;

$taskIdList = array(1, 2, 3);

// 6. 模拟不同的应用状态
global $app;
$app->viewType = 'html';
$app->tab = 'execution';

// 7. 🔴 强制要求：必须包含至少5个测试步骤
r($taskZenTest->responseAfterbatchCreateTest($taskIdList, $executionObj)) && p('result,message') && e('success,保存成功'); // 步骤1：正常批量创建任务成功情况
$app->viewType = 'json';
r($taskZenTest->responseAfterbatchCreateTest($taskIdList, $executionObj)) && p('result') && e('success'); // 步骤2：API模式下的批量创建
$app->viewType = 'html';
r($taskZenTest->responseAfterbatchCreateTest($taskIdList, $executionObj, true)) && p('result,closeModal,callback') && e('success,1,loadCurrentPage()'); // 步骤3：模态框模式下的批量创建
$app->tab = 'my';
r($taskZenTest->responseAfterbatchCreateTest($taskIdList, $executionObj)) && p('result,load') && e('success,my-work-mode=task'); // 步骤4：my标签页下的批量创建
$app->tab = 'project';
r($taskZenTest->responseAfterbatchCreateTest($taskIdList, $executionMultipleObj)) && p('result,load') && e('success,project-execution-browseType=all&projectID=2'); // 步骤5：project标签页多执行模式