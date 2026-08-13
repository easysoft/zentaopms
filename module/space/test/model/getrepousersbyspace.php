#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getRepoUsersBySpace();
timeout=0
cid=0

- 查询所有空间的仓库用户数量 @4
- 查询空间1下的仓库用户数量 @3
- 查询无效空间的仓库用户为空 @0
- 查询所有非0空间的仓库用户数量 @4
- 查询空间2下的仓库用户账号 @test2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

$repo = zenData('ops_repo');
$repo->id->range('1-3');
$repo->spaceID->range('1,2,1');
$repo->name->range('repo-one,repo-two,repo-three');
$repo->status->range('active{3}');
$repo->deleted->range('0{3}');
$repo->gen(3);

$repoUser = zenData('ops_repouser');
$repoUser->id->range('1-4');
$repoUser->repo->range('1,1,2,3');
$repoUser->account->range('admin,test1,test2,admin');
$repoUser->gen(4);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getRepoUsersBySpaceCountTest())             && p() && e('4');        // 查询所有空间的仓库用户数量
r($spaceTester->getRepoUsersBySpaceCountTest(1))            && p() && e('3');        // 查询空间1下的仓库用户数量
r($spaceTester->getRepoUsersBySpaceCountTest(9999))         && p() && e('0');        // 查询无效空间的仓库用户为空
r($spaceTester->getRepoUsersBySpaceCountTest(0))            && p() && e('4');        // 查询所有非0空间的仓库用户数量
r($spaceTester->getRepoUsersBySpaceFieldTest(2, 0, 'account')) && p() && e('test2'); // 查询空间2下的仓库用户账号
