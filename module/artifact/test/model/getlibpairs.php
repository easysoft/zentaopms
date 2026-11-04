#!/usr/bin/env php
<?php

/**

title=测试 artifactModel::getLibPairs()
timeout=0
cid=0

- 未删除的制品库全部返回 @4
- 按空间级scope查询制品库 @2
- 按空间ID列表查询制品库 @2
- 按代码库ID列表查询制品库 @1
- 按制品类型查询制品库 @4
- 查询结果保留制品库名称 @制品库1
- 已删除的制品库不返回 @0
- 不存在的空间ID返回空列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$user = zenData('user');
$user->id->range('1');
$user->account->range('admin');
$user->gen(1);

su('admin');

/* 独立准备制品库数据：共5条，其中制品库5已删除。 */
$lib = zenData('ops_artifact_libs');
$lib->id->range('1-5');
$lib->spaceID->range('1,1,2,2,1');
$lib->repoID->range('0,1,2,0,3');
$lib->name->range('制品库1,制品库2,制品库3,制品库4,制品库5');
$lib->code->range('lib1,lib2,lib3,lib4,lib5');
$lib->type->range('file{4},artifact');
$lib->scope->range('space,space,repo,repo,space');
$lib->createdBy->range('admin{5}');
$lib->deleted->range('0{4},1');
$lib->gen(5);

$tester = new artifactModelTest();

r(count($tester->getLibPairsTest())) && p() && e('4'); // 未删除的制品库全部返回
r(count($tester->getLibPairsTest('space'))) && p() && e('2'); // 按空间级scope查询制品库
r(count($tester->getLibPairsTest('', '', array(2)))) && p() && e('2'); // 按空间ID列表查询制品库
r(count($tester->getLibPairsTest('', '', array(), array(1)))) && p() && e('1'); // 按代码库ID列表查询制品库
r(count($tester->getLibPairsTest('', 'file'))) && p() && e('4'); // 按制品类型查询制品库
r($tester->getLibPairsTest()) && p('1') && e('制品库1'); // 查询结果保留制品库名称
r(count($tester->getLibPairsTest('', '', array(), array(3)))) && p() && e('0'); // 已删除的制品库不返回
r(count($tester->getLibPairsTest('', '', array(999999)))) && p() && e('0'); // 不存在的空间ID返回空列表
