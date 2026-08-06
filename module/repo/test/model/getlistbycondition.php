#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel::getListByCondition();
timeout=0
cid=18070

- 执行repoTest模块的getListByConditionIsArrayTest方法，参数是''  @1
- 执行repoTest模块的getListByConditionCountTest方法，参数是''  @5
- 执行repoTest模块的getListByConditionCountTest方法，参数是"name='testHtml'"  @1
- 执行repoTest模块的getListByConditionCountTest方法，参数是'', 2  @2
- 执行repoTest模块的getListByConditionTest方法，参数是'', 0, 'id_asc' 第0条的id属性 @1
- 执行repoTest模块的getListByConditionCountTest方法，参数是'', 0, 'id_desc', $pager  @2
- 执行repoTest模块的getListByConditionCountTest方法，参数是"name like '%test%'"  @3
- 执行repoTest模块的getListByConditionCountTest方法，参数是"name = 'nonexistent'"  @0

*/

global $tester;
zenData('ops_repo')->gen(0);
zenData('ops_spaceuser')->gen(0);

zenData('ops_space')->gen(0);
$spaceTable = zenData('ops_space');
$spaceTable->id->range('1,2');
$spaceTable->name->range('repo-test-space-1,repo-test-space-2');
$spaceTable->code->range('repo-test-space-1,repo-test-space-2');
$spaceTable->acl->range('open{2}');
$spaceTable->auth->range('extend{2}');
$spaceTable->deleted->range('0{2}');
$spaceTable->gen(2);

$repoTable = zenData('ops_repo');
$repoTable->id->range('1-7');
$repoTable->spaceID->range('1,1,2,2,1,1,1');
$repoTable->product->range('1{7}');
$repoTable->name->range('testHtml,testApi,projectRepo,testDocs,archiveRepo,hiddenRepo,deletedRepo');
$repoTable->scmType->range('git{6},svn');
$repoTable->gitUID->range('uid1,uid2,uid3,uid4,uid5,uid6,uid7');
$repoTable->providerID->range('0{7}');
$repoTable->mirror->range('0{7}');
$repoTable->acl->range('open{7}');
$repoTable->status->range('active{5},importing,active');
$repoTable->deleted->range('0{6},1');
$repoTable->gen(7);

$spaceUserTable = zenData('ops_spaceuser');
$spaceUserTable->space->range('1,2');
$spaceUserTable->role->range('manager{2}');
$spaceUserTable->account->range('admin{2}');
$spaceUserTable->gen(2);

su('admin');

$repoTest = new repoModelTest();
$repoTest->seedGitFoxEntry();

$pager = new stdclass();
$pager->recPerPage = 2;
$pager->pageID     = 1;
$repoTest->instance->app->rawModule = 'repo';
$repoTest->instance->app->rawMethod = 'browse';
$repoTest->instance->app->loadClass('pager', true);
$pager = pager::init(0, $pager->recPerPage, $pager->pageID);

r($repoTest->getListByConditionIsArrayTest('')) && p() && e('1');
r($repoTest->getListByConditionCountTest('')) && p() && e('5');
r($repoTest->getListByConditionCountTest("name='testHtml'")) && p() && e('1');
r($repoTest->getListByConditionCountTest('', 2)) && p() && e('2');
r(array_values($repoTest->getListByConditionTest('', 0, 'id_asc'))) && p('0:id') && e('1');
r($repoTest->getListByConditionCountTest('', 0, 'id_desc', $pager)) && p() && e('2');
r($repoTest->getListByConditionCountTest("name like '%test%'")) && p() && e('3');
r($repoTest->getListByConditionCountTest("name = 'nonexistent'")) && p() && e('0');
