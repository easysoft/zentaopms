#!/usr/bin/env php
<?php

/**

title=测试 mrZen::assignEditData();
timeout=0
cid=0

- 步骤1：gitlab类型正常情况属性title @编辑合并请求
- 步骤2：gitea类型正常情况属性title @编辑合并请求
- 步骤3：gogs类型且不同项目属性title @编辑合并请求
- 步骤4：空MR对象 @0
- 步骤5：空SCM类型 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

// 2. zendata数据准备
$mrTable = zenData('mr');
$mrTable->id->range('1-5');
$mrTable->hostID->range('1-3');
$mrTable->sourceProject->range('1-10');
$mrTable->sourceBranch->range('feature-branch,develop,test-branch');
$mrTable->targetProject->range('1-10');
$mrTable->targetBranch->range('master,main,develop');
$mrTable->repoID->range('1-5');
$mrTable->gen(5);

$repoTable = zenData('repo');
$repoTable->id->range('1-5');
$repoTable->SCM->range('Gitlab,Gitea,Gogs');
$repoTable->serviceHost->range('1-3');
$repoTable->serviceProject->range('1-10');
$repoTable->gen(5);

$jobTable = zenData('job');
$jobTable->id->range('1-5');
$jobTable->name->range('Test Job 1,Build Job,Deploy Job');
$jobTable->repo->range('1-5');
$jobTable->gen(5);

$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,user3');
$userTable->realname->range('管理员,用户1,用户2,用户3');
$userTable->password->range('123456{4}');
$userTable->role->range('admin,user{3}');
$userTable->gen(4);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$mrTest = new mrTest();

// 构造测试MR对象
$validMR = new stdclass();
$validMR->id = 1;
$validMR->hostID = 1;
$validMR->sourceProject = 3;
$validMR->sourceBranch = 'feature-branch';
$validMR->targetProject = 3;
$validMR->targetBranch = 'master';
$validMR->repoID = 1;
$validMR->canDeleteBranch = true;

$validMRWithDifferentProjects = new stdclass();
$validMRWithDifferentProjects->id = 2;
$validMRWithDifferentProjects->hostID = 1;
$validMRWithDifferentProjects->sourceProject = 3;
$validMRWithDifferentProjects->sourceBranch = 'develop';
$validMRWithDifferentProjects->targetProject = 5;
$validMRWithDifferentProjects->targetBranch = 'main';
$validMRWithDifferentProjects->repoID = 2;
$validMRWithDifferentProjects->canDeleteBranch = true;

$emptyMR = new stdclass();

// 5. 强制要求：必须包含至少5个测试步骤
r($mrTest->assignEditDataTest($validMR, 'gitlab')) && p('title') && e('编辑合并请求'); // 步骤1：gitlab类型正常情况
r($mrTest->assignEditDataTest($validMR, 'gitea')) && p('title') && e('编辑合并请求'); // 步骤2：gitea类型正常情况  
r($mrTest->assignEditDataTest($validMRWithDifferentProjects, 'gogs')) && p('title') && e('编辑合并请求'); // 步骤3：gogs类型且不同项目
r($mrTest->assignEditDataTest($emptyMR, 'gitlab')) && p() && e('0'); // 步骤4：空MR对象
r($mrTest->assignEditDataTest($validMR, '')) && p() && e('0'); // 步骤5：空SCM类型