#!/usr/bin/env php
<?php

/**

title=测试 projectZen::responseAfterStart();
timeout=0
cid=17965

- 步骤1：正常情况 @success
- 步骤2：只有备注 @success
- 步骤3：只有变更 @success
- 步骤4：既无备注也无变更 @success
- 步骤5：空项目对象 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目{1-10}');
$table->status->range('wait{5},doing{3},closed{2}');
$table->openedBy->range('admin');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$projectTest = new projectzenTest();

// 5. 测试步骤 - 必须包含至少5个测试步骤

// 创建测试项目对象
$project = new stdClass();
$project->id = 1;
$project->name = '测试项目';
$project->status = 'wait';

// 测试步骤1：正常情况 - 有备注和变更时创建动作日志
$change1 = new stdClass();
$change1->field = 'status';
$change1->old = 'wait';
$change1->new = 'doing';
$changes = array($change1);
$comment = '启动项目测试';
r($projectTest->responseAfterStartTest($project, $changes, $comment)) && p() && e('success'); // 步骤1：正常情况

// 测试步骤2：边界情况 - 只有备注没有变更
$changes = array();
$comment = '仅有备注';
r($projectTest->responseAfterStartTest($project, $changes, $comment)) && p() && e('success'); // 步骤2：只有备注

// 测试步骤3：边界情况 - 只有变更没有备注
$change3 = new stdClass();
$change3->field = 'PM';
$change3->old = '';
$change3->new = 'admin';
$changes = array($change3);
$comment = '';
r($projectTest->responseAfterStartTest($project, $changes, $comment)) && p() && e('success'); // 步骤3：只有变更

// 测试步骤4：边界情况 - 既无备注也无变更
$changes = array();
$comment = '';
r($projectTest->responseAfterStartTest($project, $changes, $comment)) && p() && e('success'); // 步骤4：既无备注也无变更

// 测试步骤5：业务规则验证 - 验证项目对象为空的情况
$emptyProject = new stdClass();
$emptyProject->id = 0;
$changes = array();
$comment = '';
r($projectTest->responseAfterStartTest($emptyProject, $changes, $comment)) && p() && e('success'); // 步骤5：空项目对象