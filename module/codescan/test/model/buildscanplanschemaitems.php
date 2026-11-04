#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$space = zenData('ops_space');
$space->id->range('1,2');
$space->name->range('Alpha,Beta');
$space->code->range('alpha,beta');
$space->acl->range('open{2}');
$space->auth->range('extend{2}');
$space->deleted->range('0{2}');
$space->gen(2);

$spaceUser = zenData('ops_spaceuser');
$spaceUser->id->range('1-2');
$spaceUser->space->range('1,2');
$spaceUser->account->range('admin{2}');
$spaceUser->role->range('manager{2}');
$spaceUser->gen(2);

/* 空间 1 下有普通 git 库、svn 库、镜像库各一个，空间 2 下没有代码库。 */
$repo = zenData('ops_repo');
$repo->id->range('11,21,23');
$repo->spaceID->range('1{3}');
$repo->name->range('repo-a,repo-svn,repo-mirror');
$repo->scmType->range('git,svn,git');
$repo->mirror->range('0,0,1');
$repo->gitUID->range('gituid11,gituid21,gituid23');
$repo->status->range('active{3}');
$repo->deleted->range('0{3}');
$repo->gen(3);

/**

title=测试 codescanModel->buildScanPlanSchemaItems();
timeout=0
cid=0

- 不传参数时只返回有代码库的空间分组 @1
- 分组下只列可扫描的代码库（svn 库、镜像库被过滤） @repo-a
- 指定空间ID时只返回该空间的分组 @1
- 指定代码库ID时只返回该代码库所在的分组 @1
- 指定 svn 库时返回空 @0
- 指定镜像库时返回空 @0
- 分组节点不带 value @yes

*/

$test = new codescanModelTest();

$result1 = $test->buildScanPlanSchemaItemsTest(0, 0);
$result2 = $test->buildScanPlanSchemaItemsTest(1, 0);
$result3 = $test->buildScanPlanSchemaItemsTest(0, 11);
$result4 = $test->buildScanPlanSchemaItemsTest(0, 21);
$result5 = $test->buildScanPlanSchemaItemsTest(0, 23);

r($result1['groupCount']) && p() && e('1');
r($result1['repoNames']) && p() && e('repo-a');
r($result2['groupCount']) && p() && e('1');
r($result3['groupCount']) && p() && e('1');
r($result4['groupCount']) && p() && e('0');
r($result5['groupCount']) && p() && e('0');
r($result1['noValue']) && p() && e('yes');
