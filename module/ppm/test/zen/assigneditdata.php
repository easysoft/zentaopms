#!/usr/bin/env php
<?php

/**

title=测试 mrZen::assignEditData();
timeout=0
cid=0

- 执行mrTest模块的assignEditDataTest方法，参数是$MR1, 'gitlab' 属性title @编辑合并请求
- 执行mrTest模块的assignEditDataTest方法，参数是$MR2, 'gitlab' 第MR条的canDeleteBranch属性 @1
- 执行mrTest模块的assignEditDataTest方法，参数是$MR3, 'gitlab' 第MR条的canDeleteBranch属性 @1
- 执行mrTest模块的assignEditDataTest方法，参数是$MR4, 'gitea' 属性title @编辑合并请求
- 执行mrTest模块的assignEditDataTest方法，参数是$MR5, 'gogs' 属性title @编辑合并请求
- 执行mrTest模块的assignEditDataTest方法，参数是$MR6, 'gitlab' 第MR条的canDeleteBranch属性 @1
- 执行mrTest模块的assignEditDataTest方法，参数是$MR7, 'gitlab' 属性repo @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->setMethodName('edit');

zenData('mr')->gen(0);
zenData('repo')->gen(0);
zenData('job')->gen(0);
zenData('user')->gen(10);

su('admin');

$mrTest = new mrZenTest();

// 准备测试数据1:gitlab类型MR,有repoID,源项目和目标项目相同
$MR1 = new stdclass();
$MR1->id = 1;
$MR1->hostID = 1;
$MR1->sourceProject = 100;
$MR1->targetProject = 100;
$MR1->sourceBranch = 'feature-branch';
$MR1->targetBranch = 'main';
$MR1->repoID = 0;
$MR1->mriid = 1;

// 准备测试数据2:gitlab类型MR,有repoID,源项目和目标项目相同
$MR2 = new stdclass();
$MR2->id = 2;
$MR2->hostID = 1;
$MR2->sourceProject = 200;
$MR2->targetProject = 200;
$MR2->sourceBranch = 'dev-branch';
$MR2->targetBranch = 'master';
$MR2->repoID = 0;
$MR2->mriid = 2;

// 准备测试数据3:gitlab类型MR,有repoID,源项目和目标项目不同
$MR3 = new stdclass();
$MR3->id = 3;
$MR3->hostID = 1;
$MR3->sourceProject = 300;
$MR3->targetProject = 301;
$MR3->sourceBranch = 'feature-x';
$MR3->targetBranch = 'main';
$MR3->repoID = 0;
$MR3->mriid = 3;

// 准备测试数据4:gitea类型MR
$MR4 = new stdclass();
$MR4->id = 4;
$MR4->hostID = 2;
$MR4->sourceProject = '400';
$MR4->targetProject = '400';
$MR4->sourceBranch = 'feature-y';
$MR4->targetBranch = 'develop';
$MR4->repoID = 0;
$MR4->mriid = 4;

// 准备测试数据5:gogs类型MR
$MR5 = new stdclass();
$MR5->id = 5;
$MR5->hostID = 3;
$MR5->sourceProject = '500';
$MR5->targetProject = '500';
$MR5->sourceBranch = 'hotfix';
$MR5->targetBranch = 'master';
$MR5->repoID = 0;
$MR5->mriid = 5;

// 准备测试数据6:MR没有repoID
$MR6 = new stdclass();
$MR6->id = 6;
$MR6->hostID = 1;
$MR6->sourceProject = 600;
$MR6->targetProject = 600;
$MR6->sourceBranch = 'test-branch';
$MR6->targetBranch = 'main';
$MR6->repoID = 0;
$MR6->mriid = 6;

// 准备测试数据7:gitlab类型MR,源项目和目标项目不同,无repoID
$MR7 = new stdclass();
$MR7->id = 7;
$MR7->hostID = 1;
$MR7->sourceProject = 700;
$MR7->targetProject = 701;
$MR7->sourceBranch = 'release';
$MR7->targetBranch = 'production';
$MR7->repoID = 0;
$MR7->mriid = 7;

r($mrTest->assignEditDataTest($MR1, 'gitlab')) && p('title') && e('编辑合并请求');
r($mrTest->assignEditDataTest($MR2, 'gitlab')) && p('MR:canDeleteBranch') && e('1');
r($mrTest->assignEditDataTest($MR3, 'gitlab')) && p('MR:canDeleteBranch') && e('1');
r($mrTest->assignEditDataTest($MR4, 'gitea')) && p('title') && e('编辑合并请求');
r($mrTest->assignEditDataTest($MR5, 'gogs')) && p('title') && e('编辑合并请求');
r($mrTest->assignEditDataTest($MR6, 'gitlab')) && p('MR:canDeleteBranch') && e('1');
r($mrTest->assignEditDataTest($MR7, 'gitlab')) && p('repo') && e('~~');