#!/usr/bin/env php
<?php

/**

title=测试 projectZen::responseAfterClose();
timeout=0
cid=0

- 步骤1：有评论和变更的正常关闭响应 @1
- 步骤2：仅有评论的关闭响应 @1
- 步骤3：仅有变更的关闭响应 @1
- 步骤4：无评论无变更的关闭响应 @1
- 步骤5：无效项目ID的关闭响应 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目A,项目B,项目C,项目D,项目E,项目F,项目G,项目H,项目I,项目J');
$table->status->range('wait,doing,suspended,closed');
$table->hasProduct->range('1,0');
$table->gen(10);

$actionTable = zenData('action');
$actionTable->id->range('1-20');
$actionTable->objectType->range('project');
$actionTable->objectID->range('1-10');
$actionTable->action->range('opened,edited,started,suspended,closed');
$actionTable->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$projectTest = new projectTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($projectTest->responseAfterCloseTest(1, array('status' => array('doing', 'closed')), '项目已完成所有任务，正式关闭')) && p() && e('1'); // 步骤1：有评论和变更的正常关闭响应
r($projectTest->responseAfterCloseTest(2, array(), '项目关闭，感谢大家的努力')) && p() && e('1'); // 步骤2：仅有评论的关闭响应
r($projectTest->responseAfterCloseTest(3, array('status' => array('suspended', 'closed')), '')) && p() && e('1'); // 步骤3：仅有变更的关闭响应
r($projectTest->responseAfterCloseTest(4, array(), '')) && p() && e('1'); // 步骤4：无评论无变更的关闭响应
r($projectTest->responseAfterCloseTest(999, array('status' => array('doing', 'closed')), '无效项目的关闭')) && p() && e('1'); // 步骤5：无效项目ID的关闭响应