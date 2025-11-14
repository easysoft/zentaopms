#!/usr/bin/env php
<?php

/**

title=测试 actionModel::checkActionCanUndelete();
timeout=0
cid=14880

- 步骤1：execution有项目且项目未删除 @1
- 步骤2：execution无项目 @该数据在版本升级过程中未参与数据归并流程，不支持还原。
- 步骤3：repo有正常服务器 @1
- 步骤4：repo无服务器 @该代码库没有所属的服务器，请先还原服务器再还原代码库
- 步骤5：其他对象类型默认情况 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('project1,project2,project3,project4,project5');
$projectTable->deleted->range('0{5}');
$projectTable->gen(5);

$pipelineTable = zenData('pipeline');
$pipelineTable->id->range('1-5');
$pipelineTable->name->range('server1,server2,server3,server4,server5');
$pipelineTable->deleted->range('0{5}');
$pipelineTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTest();

// 构造测试数据对象
$executionAction = new stdClass();
$executionAction->objectType = 'execution';

$executionObject = new stdClass();
$executionObject->id = 1;
$executionObject->deleted = 1;
$executionObject->project = 1;

$executionObjectNoProject = new stdClass();
$executionObjectNoProject->id = 2;
$executionObjectNoProject->deleted = 1;
$executionObjectNoProject->project = 0;

$repoAction = new stdClass();
$repoAction->objectType = 'repo';

$repoObject = new stdClass();
$repoObject->id = 1;
$repoObject->SCM = 'Gitlab';
$repoObject->serviceHost = 1;

$repoObjectNoServer = new stdClass();
$repoObjectNoServer->id = 2;
$repoObjectNoServer->SCM = 'Gitlab';
$repoObjectNoServer->serviceHost = 999;

$otherAction = new stdClass();
$otherAction->objectType = 'task';

$otherObject = new stdClass();
$otherObject->id = 1;

// 5. 强制要求：必须包含至少5个测试步骤
r($actionTest->checkActionCanUndeleteTest($executionAction, $executionObject)) && p() && e('1'); // 步骤1：execution有项目且项目未删除
r($actionTest->checkActionCanUndeleteTest($executionAction, $executionObjectNoProject)) && p() && e('该数据在版本升级过程中未参与数据归并流程，不支持还原。'); // 步骤2：execution无项目
r($actionTest->checkActionCanUndeleteTest($repoAction, $repoObject)) && p() && e('1'); // 步骤3：repo有正常服务器
r($actionTest->checkActionCanUndeleteTest($repoAction, $repoObjectNoServer)) && p() && e('该代码库没有所属的服务器，请先还原服务器再还原代码库'); // 步骤4：repo无服务器
r($actionTest->checkActionCanUndeleteTest($otherAction, $otherObject)) && p() && e('1'); // 步骤5：其他对象类型默认情况