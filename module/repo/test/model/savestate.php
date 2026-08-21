#!/usr/bin/env php
<?php

/**

title=测试 repoModel::saveState();
timeout=0
cid=18101

- 步骤1：正常设置有效的代码库ID @2
- 步骤2：设置无效的代码库ID @1
- 步骤3：不传入代码库ID且session中无repoID @1
- 步骤4：在project tab下设置代码库ID @1
- 步骤5：测试边界值repoID为0的情况 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
zenData('ops_repo')->gen(0);
zenData('ops_spaceuser')->gen(0);

zenData('ops_space')->gen(0);
$spaceTable = zenData('ops_space');
$spaceTable->id->range('1');
$spaceTable->name->range('repo-test-space');
$spaceTable->code->range('repo-test-space');
$spaceTable->acl->range('open');
$spaceTable->auth->range('extend');
$spaceTable->deleted->range('0');
$spaceTable->gen(1);

zenData('projectproduct')->gen(0);
$projectProductTable = zenData('projectproduct');
$projectProductTable->project->range('11');
$projectProductTable->product->range('1');
$projectProductTable->branch->range('0');
$projectProductTable->plan->range('');
$projectProductTable->roadmap->range('');
$projectProductTable->gen(1);

$repoTable = zenData('ops_repo');
$repoTable->id->range('1,2');
$repoTable->spaceID->range('1{2}');
$repoTable->product->range('1,2');
$repoTable->name->range('repo1,repo2');
$repoTable->scmType->range('git{2}');
$repoTable->gitUID->range('uid1,uid2');
$repoTable->providerID->range('0{2}');
$repoTable->mirror->range('0{2}');
$repoTable->acl->range('open{2}');
$repoTable->status->range('active{2}');
$repoTable->deleted->range('0{2}');
$repoTable->gen(2);

$spaceUserTable = zenData('ops_spaceuser');
$spaceUserTable->space->range('1');
$spaceUserTable->role->range('manager');
$spaceUserTable->account->range('admin');
$spaceUserTable->gen(1);

su('admin');

$repo = new repoModelTest();
$repo->seedGitFoxEntry();

r($repo->saveStateTest(2)) && p() && e('2'); // 步骤1：正常设置有效的代码库ID
r($repo->saveStateTest(10001)) && p() && e('1'); // 步骤2：设置无效的代码库ID
r($repo->saveStateTest()) && p() && e('1'); // 步骤3：不传入代码库ID且session中无repoID
$repo->objectModel->app->tab = 'project';
r($repo->saveStateTest(2, 11)) && p() && e('1'); // 步骤4：在project tab下设置代码库ID
r($repo->saveStateTest(0)) && p() && e('1'); // 步骤5：测试边界值repoID为0的情况
