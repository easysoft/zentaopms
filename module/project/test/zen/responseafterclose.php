#!/usr/bin/env php
<?php

/**

title=测试 projectZen::responseAfterClose();
timeout=0
cid=17964

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
$table->status->range('doing{5},wait{3},suspended{2}');
$table->openedBy->range('admin');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$projectTest = new projectzenTest();

// 5. 测试步骤 - 必须包含至少5个测试步骤

// 测试步骤1：正常情况 - 有备注和变更时创建动作日志
$projectID = 1;
$change1 = new stdClass();
$change1->field = 'status';
$change1->old = 'doing';
$change1->new = 'closed';
$changes = array($change1);
$comment = '关闭项目测试';
r($projectTest->responseAfterCloseTest($projectID, $changes, $comment)) && p() && e('success'); // 步骤1：正常情况

// 测试步骤2：边界情况 - 只有备注没有变更
$projectID = 2;
$changes = array();
$comment = '仅有备注关闭项目';
r($projectTest->responseAfterCloseTest($projectID, $changes, $comment)) && p() && e('success'); // 步骤2：只有备注

// 测试步骤3：边界情况 - 只有变更没有备注
$projectID = 3;
$change3 = new stdClass();
$change3->field = 'closedBy';
$change3->old = '';
$change3->new = 'admin';
$changes = array($change3);
$comment = '';
r($projectTest->responseAfterCloseTest($projectID, $changes, $comment)) && p() && e('success'); // 步骤3：只有变更

// 测试步骤4：边界情况 - 既无备注也无变更
$projectID = 4;
$changes = array();
$comment = '';
r($projectTest->responseAfterCloseTest($projectID, $changes, $comment)) && p() && e('success'); // 步骤4：既无备注也无变更

// 测试步骤5：业务规则验证 - 验证项目ID为0的情况
$projectID = 0;
$changes = array();
$comment = '';
r($projectTest->responseAfterCloseTest($projectID, $changes, $comment)) && p() && e('success'); // 步骤5：空项目对象