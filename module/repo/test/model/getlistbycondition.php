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
- 执行repoTest模块的getListByConditionCountTest方法，参数是''  @6
- 执行repoTest模块的getListByConditionCountTest方法，参数是"name='testHtml'"  @1
- 执行repoTest模块的getListByConditionCountTest方法，参数是'', 2  @2
- 执行repoTest模块的getListByConditionTest方法，参数是'', 0, 'id_asc' 第0条的id属性 @1
- 执行repoTest模块的getListByConditionCountTest方法，参数是'', 0, 'id_desc', $pager  @2
- 执行repoTest模块的getListByConditionCountTest方法，参数是"name like '%test%'"  @3
- 执行repoTest模块的getListByConditionCountTest方法，参数是"name = 'nonexistent'"  @0
- 普通用户不属于任何空间时只返回公开空间代码库 @5
- 普通用户不属于任何空间时不返回私有空间代码库 @0
- 普通用户指定未授权私有空间时不返回代码库 @0
- 私有空间成员可以查看该空间的代码库 @1
- 私有空间成员获取全部空间代码库数量 @6

*/

global $tester;
zenData('user')->gen(10);
zenData('ops_repo')->gen(0);
zenData('ops_repouser')->gen(0);
zenData('ops_spaceuser')->gen(0);
zenData('group')->gen(0);
zenData('usergroup')->gen(0);

zenData('ops_space')->gen(0);
$spaceTable = zenData('ops_space');
$spaceTable->id->range('1-3');
$spaceTable->name->range('repo-test-space-1,repo-test-space-2,repo-test-private-space');
$spaceTable->code->range('repo-test-space-1,repo-test-space-2,repo-test-private-space');
$spaceTable->acl->range('open{2},private');
$spaceTable->auth->range('extend{3}');
$spaceTable->deleted->range('0{3}');
$spaceTable->gen(3);

$repoTable = zenData('ops_repo');
$repoTable->id->range('1-8');
$repoTable->spaceID->range('1,1,2,2,1,1,1,3');
$repoTable->product->range('1{8}');
$repoTable->name->range('testHtml,testApi,projectRepo,testDocs,archiveRepo,hiddenRepo,deletedRepo,privSpaceRepo');
$repoTable->scmType->range('git{6},svn,git');
$repoTable->gitUID->range('uid1,uid2,uid3,uid4,uid5,uid6,uid7,uid8');
$repoTable->providerID->range('0{8}');
$repoTable->mirror->range('0{8}');
$repoTable->acl->range('open{8}');
$repoTable->status->range('active{5},importing,active,active');
$repoTable->deleted->range('0{6},1,0');
$repoTable->gen(8);

$spaceUserTable = zenData('ops_spaceuser');
$spaceUserTable->space->range('1,2,3,3');
$spaceUserTable->role->range('manager,manager,manager,member');
$spaceUserTable->account->range('admin,admin,admin,user2');
$spaceUserTable->gen(4);

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
r($repoTest->getListByConditionCountTest('')) && p() && e('6');
r($repoTest->getListByConditionCountTest("name='testHtml'")) && p() && e('1');
r($repoTest->getListByConditionCountTest('', 2)) && p() && e('2');
r(array_values($repoTest->getListByConditionTest('', 0, 'id_asc'))) && p('0:id') && e('1');
r($repoTest->getListByConditionCountTest('', 0, 'id_desc', $pager)) && p() && e('2');
r($repoTest->getListByConditionCountTest("name like '%test%'")) && p() && e('3');
r($repoTest->getListByConditionCountTest("name = 'nonexistent'")) && p() && e('0');

su('user1');
r($repoTest->getListByConditionCountTest('', 0, 'id_asc'))     && p() && e('5'); // 普通用户不属于任何空间时只返回公开空间代码库
r($repoTest->getListByConditionHasKeyTest('', 0, 'id_asc', 8)) && p() && e('0'); // 普通用户不属于任何空间时不返回私有空间代码库
r($repoTest->getListByConditionCountTest('', 3))               && p() && e('0'); // 普通用户指定未授权私有空间时不返回代码库

su('user2');
r($repoTest->getListByConditionHasKeyTest('', 0, 'id_asc', 8)) && p() && e('1'); // 私有空间成员可以查看该空间的代码库
r($repoTest->getListByConditionCountTest('', 0, 'id_asc'))     && p() && e('6'); // 私有空间成员获取全部空间代码库数量
