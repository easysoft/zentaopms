#!/usr/bin/env php
<?php

/**

title=测试 mrZen::checkProjectEdit();
timeout=0
cid=0

- 步骤1：gitlab正常权限检查 @1
- 步骤2：gitea允许合并提交 @1
- 步骤3：gogs有推送权限 @1
- 步骤4：不支持的主机类型 @unsupported_hosttype
- 步骤5：无效的主机类型参数 @invalid_hosttype

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('mr');
$table->id->range('1-10');
$table->hostID->range('1-3');
$table->sourceProject->range('project1,project2,project3');
$table->sourceBranch->range('main,develop,feature');
$table->targetProject->range('project1,project2,project3');
$table->targetBranch->range('main,develop,feature');
$table->mriid->range('1-10');
$table->title->range('Test MR 1,Test MR 2,Test MR 3');
$table->status->range('opened,merged,closed');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$mrTest = new mrTest();

// 创建测试用的sourceProject对象
$gitlabProject = new stdclass();
$gitlabProject->id = 123;
$gitlabProject->name = 'test-project';

$giteaProject = new stdclass();
$giteaProject->allow_merge_commits = true;
$giteaProject->name = 'gitea-project';

$gogsProject = new stdclass();
$gogsProject->permissions = new stdclass();
$gogsProject->permissions->push = true;
$gogsProject->name = 'gogs-project';

$giteaProjectNoPermission = new stdclass();
$giteaProjectNoPermission->allow_merge_commits = false;
$giteaProjectNoPermission->name = 'gitea-no-permission';

// 创建测试用的MR对象
$MR = new stdclass();
$MR->id = 1;
$MR->hostID = 1;
$MR->sourceProject = 'project1';
$MR->sourceBranch = 'feature';
$MR->targetProject = 'project1';
$MR->targetBranch = 'main';
$MR->title = 'Test MR';

// 5. 强制要求：必须包含至少5个测试步骤
r($mrTest->checkProjectEditTest('gitlab', $gitlabProject, $MR)) && p() && e('1'); // 步骤1：gitlab正常权限检查
r($mrTest->checkProjectEditTest('gitea', $giteaProject, $MR)) && p() && e('1'); // 步骤2：gitea允许合并提交
r($mrTest->checkProjectEditTest('gogs', $gogsProject, $MR)) && p() && e('1'); // 步骤3：gogs有推送权限
r($mrTest->checkProjectEditTest('bitbucket', $gitlabProject, $MR)) && p() && e('unsupported_hosttype'); // 步骤4：不支持的主机类型
r($mrTest->checkProjectEditTest('', $gitlabProject, $MR)) && p() && e('invalid_hosttype'); // 步骤5：无效的主机类型参数