#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getReposBySpace();
timeout=0
cid=16030

- 查询无效的空间 @0
- 查询空间1下的代码库数量 @2
- 查询空间1下的第1个代码库名称 @space-one-main
- 查询空间2下的代码库类型 @svn
- 查询空间1下私有代码库数量 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

$repo = zenData('ops_repo');
$repo->id->range('1-3');
$repo->spaceID->range('1,1,2');
$repo->product->range('1,2,3');
$repo->name->range('space-one-main,space-one-private,space-two-svn');
$repo->scmType->range('git,git,svn');
$repo->gitUID->range('repo-gituid-1,repo-gituid-2,repo-gituid-3');
$repo->acl->range('open,private,open');
$repo->status->range('active{3}');
$repo->deleted->range('0{3}');
$repo->gen(3);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getReposBySpaceCountTest(0))           && p() && e('0');              // 查询无效的空间
r($spaceTester->getReposBySpaceCountTest(1))           && p() && e('2');              // 查询空间1下的代码库数量
r($spaceTester->getReposBySpaceFieldTest(1, 1, 'name'))    && p() && e('space-one-main');    // 查询空间1下的第1个代码库名称
r($spaceTester->getReposBySpaceFieldTest(2, 3, 'scmType')) && p() && e('svn');               // 查询空间2下的代码库类型
r($spaceTester->getReposBySpaceCountTest(1, 'private'))    && p() && e('1');                 // 查询空间1下私有代码库数量
