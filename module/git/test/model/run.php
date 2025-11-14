#!/usr/bin/env php
<?php

/**

title=测试 gitModel::run();
timeout=0
cid=16551

- 步骤1：无仓库时返回false @0
- 步骤2：有仓库时正常执行 @1
- 步骤3：Gitlab仓库处理成功 @1
- 步骤4：标签任务处理成功 @1
- 步骤5：多仓库混合处理成功 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/git.unittest.class.php';

su('admin');

$git = new gitTest();

// 测试步骤1：无仓库情况下执行任务
zenData('repo')->gen(0);
zenData('job')->gen(0);
zenData('repohistory')->gen(0);
r($git->runTest()) && p() && e('0'); // 步骤1：无仓库时返回false

// 测试步骤2：正常仓库情况下执行任务
zenData('repo')->loadYaml('repo')->gen(1);
zenData('job')->gen(0);
zenData('repohistory')->gen(0);
r($git->runTest()) && p() && e('1'); // 步骤2：有仓库时正常执行

// 测试步骤3：包含Gitlab仓库执行任务
$repo = zenData('repo');
$repo->id->range('1');
$repo->SCM->range('Gitlab');
$repo->serviceHost->range('1');
$repo->serviceProject->range('42');
$repo->gen(1);
zenData('job')->gen(0);
zenData('repohistory')->gen(0);
r($git->runTest()) && p() && e('1'); // 步骤3：Gitlab仓库处理成功

// 测试步骤4：包含标签触发任务的仓库
zenData('repo')->loadYaml('repo')->gen(1);
$job = zenData('job');
$job->id->range('1');
$job->repo->range('1');
$job->triggerType->range('tag');
$job->lastTag->range('v1.0.0');
$job->gen(1);
zenData('repohistory')->gen(0);
r($git->runTest()) && p() && e('1'); // 步骤4：标签任务处理成功

// 测试步骤5：包含多个仓库混合执行
$repo = zenData('repo');
$repo->id->range('1-3');
$repo->SCM->range('Git{2},Gitlab{1}');
$repo->serviceHost->range('1{3}');
$repo->serviceProject->range('42{3}');
$repo->gen(3);
$job = zenData('job');
$job->id->range('1-2');
$job->repo->range('1,2');
$job->triggerType->range('commit,tag');
$job->lastTag->range('[]{1},v2.0.0{1}');
$job->gen(2);
zenData('repohistory')->gen(0);
r($git->runTest()) && p() && e('1'); // 步骤5：多仓库混合处理成功