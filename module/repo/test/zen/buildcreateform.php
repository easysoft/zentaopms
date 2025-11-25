#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildCreateForm();
timeout=0
cid=18124

- 步骤1：正常项目ID属性title @代码库-创建
- 步骤2：项目tab下的表单构建属性objectID @2
- 步骤3：execution tab下的表单构建属性objectID @3
- 步骤4：objectID为0的情况属性objectID @0
- 步骤5：不存在的项目ID属性title @代码库-创建

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('repo1,repo2,repo3,repo4,repo5');
$table->SCM->range('Git{3},Subversion{2}');
$table->product->range('1,2,3,1,2');
$table->projects->range('1,2,3,1,2');
$table->gen(5);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->status->range('normal{4},closed{1}');
$productTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->status->range('wait{2},doing{2},done{1}');
$projectTable->gen(5);

$groupTable = zenData('group');
$groupTable->id->range('1-3');
$groupTable->name->range('管理员,开发人员,测试人员');
$groupTable->gen(3);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->deleted->range('0{4},1{1}');
$userTable->gen(5);

$pipelineTable = zenData('pipeline');
$pipelineTable->id->range('1-2');
$pipelineTable->name->range('GitLab服务器,Jenkins服务器');
$pipelineTable->type->range('gitlab,jenkins');
$pipelineTable->gen(2);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->buildCreateFormTest(1)) && p('title') && e('代码库-创建'); // 步骤1：正常项目ID
r($repoTest->buildCreateFormTest(2)) && p('objectID') && e('2'); // 步骤2：项目tab下的表单构建
r($repoTest->buildCreateFormTest(3)) && p('objectID') && e('3'); // 步骤3：execution tab下的表单构建
r($repoTest->buildCreateFormTest(0)) && p('objectID') && e('0'); // 步骤4：objectID为0的情况
r($repoTest->buildCreateFormTest(999)) && p('title') && e('代码库-创建'); // 步骤5：不存在的项目ID