#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getList();
timeout=0
cid=0

- 获取代码库1的分支类型键值对属性1 @branch_type1
- 获取代码库1的分支类型数量 @1
- 获取代码库2的分支类型键值对属性2 @branch_type2
- 获取代码库3的分支类型键值对属性3 @branch_type3
- 获取代码库4的分支类型数量 @1

*/

$repoTable = zenData('ops_repo');
$repoTable->id->range('1-10');
$repoTable->name->range('repo1,repo2,repo3,repo4,repo5,repo6,repo7,repo8,repo9,repo10');
$repoTable->gitUID->range('uid0001,uid0002,uid0003,uid0004,uid0005,uid0006,uid0007,uid0008,uid0009,uid0010');
$repoTable->spaceID->range('1');
$repoTable->gen(10);

$typeTable = zenData('ops_branch_type');
$typeTable->id->range('1-10');
$typeTable->repo->range('1-10');
$typeTable->name->range('branch_type1,branch_type2,branch_type3,branch_type4,branch_type5,branch_type6,branch_type7,branch_type8,branch_type9,branch_type10');
$typeTable->key->range('branch_type1,branch_type2,branch_type3,branch_type4,branch_type5,branch_type6,branch_type7,branch_type8,branch_type9,branch_type10');
$typeTable->prefix->range('branch_type1,branch_type2,branch_type3,branch_type4,branch_type5,branch_type6,branch_type7,branch_type8,branch_type9,branch_type10');
$typeTable->deleted->range('0');
$typeTable->gen(10);

$repo = new repobranchtypeTest();
r($repo->getBranchTypePairsTest(1))        && p('1') && e('branch_type1'); // 获取代码库1的分支类型键值对
r(count($repo->getBranchTypePairsTest(1))) && p()    && e('1');            // 获取代码库1的分支类型数量
r($repo->getBranchTypePairsTest(2))        && p('2') && e('branch_type2'); // 获取代码库2的分支类型键值对
r($repo->getBranchTypePairsTest(3))        && p('3') && e('branch_type3'); // 获取代码库3的分支类型键值对
r(count($repo->getBranchTypePairsTest(4))) && p()    && e('1');            // 获取代码库4的分支类型数量