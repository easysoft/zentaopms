#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getRepoPairs();
timeout=0
cid=18078

- 执行repoTest模块的getRepoPairsTest方法，参数是$typeList[1]
 - 属性1 @testHtml
 - 属性4 @testSvn
- 执行repoTest模块的getRepoPairsCountTest方法，参数是$typeList[1]  @4
- 执行repoTest模块的getRepoPairsTest方法，参数是$typeList[0], $projectID 属性1 @testHtml
- 执行repoTest模块的getRepoPairsCountTest方法，参数是$typeList[0], $projectID  @1
- 执行repoTest模块的getRepoPairsTest方法，参数是$typeList[1], 0, false 属性2 @project1

*/

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
$repoTable->id->range('1-4');
$repoTable->spaceID->range('1{4}');
$repoTable->product->range('1,2,3,4');
$repoTable->name->range('testHtml,project1,unittest,testSvn');
$repoTable->scmType->range('git{4}');
$repoTable->gitUID->range('uid1,uid2,uid3,uid4');
$repoTable->providerID->range('0{4}');
$repoTable->mirror->range('0{4}');
$repoTable->acl->range('open{4}');
$repoTable->status->range('active{4}');
$repoTable->deleted->range('0{4}');
$repoTable->gen(4);

$spaceUserTable = zenData('ops_spaceuser');
$spaceUserTable->space->range('1');
$spaceUserTable->role->range('manager');
$spaceUserTable->account->range('admin');
$spaceUserTable->gen(1);

su('admin');

$repo       = $tester->loadModel('repo');
$repoTest   = new repoModelTest();
$repoTest->seedGitFoxEntry();

$typeList  = array('project', 'repo');
$projectID = 11;

r($repoTest->getRepoPairsTest($typeList[1])) && p('1,4') && e('testHtml,testSvn');
r($repoTest->getRepoPairsCountTest($typeList[1])) && p() && e('4');
r($repoTest->getRepoPairsTest($typeList[0], $projectID)) && p('1') && e('testHtml');
r($repoTest->getRepoPairsCountTest($typeList[0], $projectID)) && p() && e('1');
r($repoTest->getRepoPairsTest($typeList[1], 0, false)) && p('2') && e('project1');
