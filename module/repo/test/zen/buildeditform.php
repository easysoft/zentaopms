#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildEditForm();
timeout=0
cid=0

- 步骤1：正常版本库ID编辑表单标题属性title @代码库-编辑
- 步骤2：正常版本库ID检查repoID属性属性repoID @1
- 步骤3：不存在的版本库ID @0
- 步骤4：Git类型版本库编辑表单第repo条的SCM属性 @Git
- 步骤5：Gitlab类型版本库获取项目信息第project条的id属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('repo1,repo2,repo3,repo4,repo5,repo6,repo7,repo8,repo9,repo10');
$table->SCM->range('Git{3},Subversion{2},Gitlab,Gitea,Gogs{3}');
$table->product->range('1,2,3,1,2,3,1,2,3,1');
$table->projects->range('1,2,3,1,2,3,1,2,3,1');
$table->client->range('git,git,git,svn,svn,"git","git","git","git","git"');
$table->path->range('/path/to/repo1,/path/to/repo2,/path/to/repo3,/path/to/repo4,/path/to/repo5,http://gitlab.com/project1,http://gitea.com/project1,http://gogs.com/project1,http://gogs.com/project2,http://gogs.com/project3');
$table->serviceProject->range('0{5},1,1,1,1,1');
$table->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$productTable->status->range('normal{8},closed{2}');
$productTable->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$projectTable->status->range('wait{3},doing{4},done{3}');
$projectTable->gen(10);

$groupTable = zenData('group');
$groupTable->id->range('1-5');
$groupTable->name->range('管理员,开发人员,测试人员,产品经理,项目经理');
$groupTable->gen(5);

$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$userTable->deleted->range('0{8},1{2}');
$userTable->gen(10);

$pipelineTable = zenData('pipeline');
$pipelineTable->id->range('1-3');
$pipelineTable->name->range('GitLab服务器,Jenkins服务器,Gitea服务器');
$pipelineTable->type->range('gitlab,jenkins,gitea');
$pipelineTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->buildEditFormTest(1, 1)) && p('title') && e('代码库-编辑'); // 步骤1：正常版本库ID编辑表单标题
r($repoTest->buildEditFormTest(1, 1)) && p('repoID') && e('1'); // 步骤2：正常版本库ID检查repoID属性
r($repoTest->buildEditFormTest(999, 1)) && p() && e('0'); // 步骤3：不存在的版本库ID
r($repoTest->buildEditFormTest(1, 1)) && p('repo:SCM') && e('Git'); // 步骤4：Git类型版本库编辑表单
r($repoTest->buildEditFormTest(6, 1)) && p('project:id') && e('1'); // 步骤5：Gitlab类型版本库获取项目信息