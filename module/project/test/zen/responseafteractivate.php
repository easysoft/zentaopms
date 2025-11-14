#!/usr/bin/env php
<?php

/**

title=测试 projectZen::responseAfterActivate();
timeout=0
cid=17963

- 执行projectzenTest模块的responseAfterActivateTest方法，参数是1, array  @success
- 执行projectzenTest模块的responseAfterActivateTest方法，参数是2, array  @success
- 执行projectzenTest模块的responseAfterActivateTest方法，参数是3, array  @success
- 执行projectzenTest模块的responseAfterActivateTest方法，参数是4, array  @success
- 执行projectzenTest模块的responseAfterActivateTest方法，参数是null, array  @projectID parameter cannot be null

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->code->range('project1,project2,project3,project4,project5,project6,project7,project8,project9,project10');
$project->status->range('wait{3},doing{3},suspended{2},closed{2}');
$project->hasProduct->range('1{5},0{5}');
$project->multiple->range('1{6},0{4}');
$project->model->range('scrum{4},waterfall{3},kanban{3}');
$project->gen(10);

$action = zenData('action');
$action->id->range('1-20');
$action->objectType->range('project{20}');
$action->objectID->range('1-10');
$action->actor->range('admin{20}');
$action->action->range('Activated{5},Started{5},Suspended{5},Closed{5}');
$action->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectzenTest = new projectzenTest();

// 5. 设置全局POST数据为测试做准备
global $_POST;

// 测试步骤1：有评论有变更的情况
$_POST['comment'] = '激活项目的评论';
r($projectzenTest->responseAfterActivateTest(1, array('status' => array('suspended', 'doing')))) && p() && e('success');

// 测试步骤2：有评论无变更的情况
$_POST['comment'] = '激活项目';
r($projectzenTest->responseAfterActivateTest(2, array())) && p() && e('success');

// 测试步骤3：无评论有变更的情况
$_POST['comment'] = '';
r($projectzenTest->responseAfterActivateTest(3, array('begin' => array('2024-01-01', '2024-02-01')))) && p() && e('success');

// 测试步骤4：无评论无变更的情况
$_POST['comment'] = '';
r($projectzenTest->responseAfterActivateTest(4, array())) && p() && e('success');

// 测试步骤5：无效项目ID的处理
r($projectzenTest->responseAfterActivateTest(null, array())) && p() && e('projectID parameter cannot be null');