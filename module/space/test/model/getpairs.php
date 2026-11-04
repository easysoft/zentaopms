#!/usr/bin/env php
<?php
/**

title=测试 spaceModel::getPairs();
timeout=0
cid=16028

- 管理员模式下返回第1个真实空间名称匹配动态创建值 @1
- 非管理员模式下返回test1的reset空间名称匹配动态创建值 @1
- 非管理员模式下查询不存在用户返回当前公开空间数量 @2
- 非管理员模式下查询test2返回当前公开空间数量 @2
- filterRepoCreate=true时空间成员返回全部所属空间数量 @3
- filterRepoCreate=true时空间分组成员返回全部所属空间数量 @3
- filterRepoCreate=true时仅代码库成员不返回私有空间数量 @2
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(0);
zenData('ops_spaceuser')->gen(0);
zenData('group')->gen(0);
zenData('usergroup')->gen(0);
zenData('ops_repo')->gen(0);
zenData('ops_repouser')->gen(0);
zenData('entry')->loadYaml('entry')->gen(1);

su('admin');

$spaceTester = new spaceModelTest();
$suffix      = date('YmdHis') . mt_rand(1000, 9999);

/* 清理历史测试遗留的 GitFox 空间，保证用例可重复执行。*/
$oldSpaces = $spaceTester->instance->getByIdList(array(), false);
foreach($oldSpaces as $oldSpace)
{
    if(strpos((string)zget($oldSpace, 'name', ''), 'ut-') === 0) $spaceTester->instance->deleteSpace((int)$oldSpace->id);
}

$spaceA = new stdClass();
$spaceA->name        = "ut-pairs-space-a-{$suffix}";
$spaceA->code        = "utpairsspacea{$suffix}";
$spaceA->desc        = 'getPairs space A';
$spaceA->acl         = 'private';
$spaceA->auth        = 'extend';
$spaceA->createdBy   = 'admin';
$spaceA->createdDate = '2026-07-29 10:18:00';

$spaceB = new stdClass();
$spaceB->name        = "ut-pairs-space-b-{$suffix}";
$spaceB->code        = "utpairsspaceb{$suffix}";
$spaceB->desc        = 'getPairs space B';
$spaceB->acl         = 'open';
$spaceB->auth        = 'extend';
$spaceB->createdBy   = 'admin';
$spaceB->createdDate = '2026-07-29 10:18:00';

$spaceC = new stdClass();
$spaceC->name        = "ut-pairs-space-c-{$suffix}";
$spaceC->code        = "utpairsspacec{$suffix}";
$spaceC->desc        = 'getPairs space C';
$spaceC->acl         = 'open';
$spaceC->auth        = 'extend';
$spaceC->createdBy   = 'admin';
$spaceC->createdDate = '2026-07-29 10:18:00';

$spaceID1 = (int)$spaceTester->createTest($spaceA);
$spaceID2 = (int)$spaceTester->createTest($spaceB);
$spaceID3 = (int)$spaceTester->createTest($spaceC);

$spaceUser = zenData('ops_spaceuser');
$spaceUser->id->range('111-116');
$spaceUser->space->range("{$spaceID1},{$spaceID2},{$spaceID3},{$spaceID1},{$spaceID2},{$spaceID3}");
$spaceUser->account->range('test1,test1,test1,test2,test2,test2');
$spaceUser->role->range('member{6}');
$spaceUser->gen(6);

$repo = zenData('ops_repo');
$repo->id->range('801');
$repo->spaceID->range("{$spaceID1}");
$repo->product->range('1');
$repo->name->range("ut-pairs-repo-{$suffix}");
$repo->status->range('active');
$repo->deleted->range('0');
$repo->gen(1);

$repoUser = zenData('ops_repouser');
$repoUser->id->range('811');
$repoUser->repo->range('801');
$repoUser->account->range('test3');
$repoUser->gen(1);

$group = zenData('group');
$group->id->range('821');
$group->name->range("ut-grp-{$suffix}");
$group->project->range('0');
$group->devopsSpace->range("{$spaceID1}");
$group->gen(1);

$userGroup = zenData('usergroup');
$userGroup->id->range('831');
$userGroup->account->range('test4');
$userGroup->group->range('821');
$userGroup->project->range('0');
$userGroup->gen(1);

r($spaceTester->getPairsFieldEqualsTest('', $spaceID1, "ut-pairs-space-a-{$suffix}")) && p() && e('1'); // 管理员模式下返回第1个真实空间名称匹配动态创建值

$tester->app->user->admin = false;
$spaceTester->instance->app->user->admin = false;

r($spaceTester->getPairsFieldEqualsTest('test1', $spaceID2, "ut-pairs-space-b-{$suffix}")) && p() && e('1'); // 非管理员模式下返回test1的reset空间名称匹配动态创建值
r($spaceTester->getPairsCountTest('notexist'))           && p() && e('2');                            // 非管理员模式下查询不存在用户返回当前公开空间数量
r($spaceTester->getPairsCountTest('test2'))              && p() && e('3');                            // 非管理员模式下查询test2返回当前空间数量
r($spaceTester->getPairsCountTest('test1', true))        && p() && e('3');                            // filterRepoCreate=true时空间成员可见私有空间
r($spaceTester->getPairsCountTest('test4', true))        && p() && e('3');                            // filterRepoCreate=true时空间分组成员可见私有空间
r($spaceTester->getPairsCountTest('test3', true))        && p() && e('2');                            // filterRepoCreate=true时仅代码库成员不可见私有空间

/* 清理外部 GitFox 空间中创建的测试数据，保证用例可重复执行。*/
$tester->app->user->admin = true;
$spaceTester->instance->app->user->admin = true;
$spaceTester->cleanSpaceRecordsTest($spaceID1);
$spaceTester->deleteSpaceSuccessTest($spaceID1);
$spaceTester->deleteSpaceSuccessTest($spaceID2);
$spaceTester->deleteSpaceSuccessTest($spaceID3);
