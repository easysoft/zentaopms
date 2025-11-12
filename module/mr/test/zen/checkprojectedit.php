#!/usr/bin/env php
<?php

/**

title=测试 mrZen::checkProjectEdit();
timeout=0
cid=0

- 执行mrTest模块的checkProjectEditTest方法，参数是'gitlab', $sourceProject, $MR  @0
- 执行mrTest模块的checkProjectEditTest方法，参数是'gitlab', $sourceProject, $MR  @0
- 执行mrTest模块的checkProjectEditTest方法，参数是'gitea', $sourceProject, $MR  @1
- 执行mrTest模块的checkProjectEditTest方法，参数是'gitea', $sourceProject, $MR  @0
- 执行mrTest模块的checkProjectEditTest方法，参数是'gogs', $sourceProject, $MR  @1
- 执行mrTest模块的checkProjectEditTest方法，参数是'gogs', $sourceProject, $MR  @0
- 执行mrTest模块的checkProjectEditTest方法，参数是'unknown', $sourceProject, $MR  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->setMethodName('view');

zenData('mr')->gen(0);
zenData('pipeline')->gen(0);

su('admin');

$mrTest = new mrZenTest();

// 测试步骤1: GitLab类型,用户已绑定且有开发者权限
$sourceProject = new stdclass();
$sourceProject->id = 1;
$MR = new stdclass();
$MR->hostID = 1;
$MR->sourceProject = 1;
r($mrTest->checkProjectEditTest('gitlab', $sourceProject, $MR)) && p() && e('0');

// 测试步骤2: GitLab类型,用户未绑定
$sourceProject = new stdclass();
$sourceProject->id = 2;
$MR = new stdclass();
$MR->hostID = 1;
$MR->sourceProject = 2;
r($mrTest->checkProjectEditTest('gitlab', $sourceProject, $MR)) && p() && e('0');

// 测试步骤3: Gitea类型,项目允许合并提交
$sourceProject = new stdclass();
$sourceProject->allow_merge_commits = true;
$MR = new stdclass();
$MR->hostID = 1;
$MR->sourceProject = 1;
r($mrTest->checkProjectEditTest('gitea', $sourceProject, $MR)) && p() && e('1');

// 测试步骤4: Gitea类型,项目不允许合并提交
$sourceProject = new stdclass();
$sourceProject->allow_merge_commits = false;
$MR = new stdclass();
$MR->hostID = 1;
$MR->sourceProject = 2;
r($mrTest->checkProjectEditTest('gitea', $sourceProject, $MR)) && p() && e('0');

// 测试步骤5: Gogs类型,用户有推送权限
$sourceProject = new stdclass();
$sourceProject->permissions = new stdclass();
$sourceProject->permissions->push = true;
$MR = new stdclass();
$MR->hostID = 1;
$MR->sourceProject = 1;
r($mrTest->checkProjectEditTest('gogs', $sourceProject, $MR)) && p() && e('1');

// 测试步骤6: Gogs类型,用户无推送权限
$sourceProject = new stdclass();
$sourceProject->permissions = new stdclass();
$sourceProject->permissions->push = false;
$MR = new stdclass();
$MR->hostID = 1;
$MR->sourceProject = 2;
r($mrTest->checkProjectEditTest('gogs', $sourceProject, $MR)) && p() && e('0');

// 测试步骤7: 未知主机类型
$sourceProject = new stdclass();
$MR = new stdclass();
$MR->hostID = 1;
$MR->sourceProject = 1;
r($mrTest->checkProjectEditTest('unknown', $sourceProject, $MR)) && p() && e('0');