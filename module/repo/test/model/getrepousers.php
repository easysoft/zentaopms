#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getRepoUsers 方法();
timeout=0
cid=0

- 查询id为1的代码库用户属性admin @admin
- 查询不存在的代码库用户 @0
- 查询id为3的代码库用户属性test2 @test2
- 查询id为4的代码库用户属性test3 @test3
- 查询id为4的代码库用户总数 @1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_repouser')->gen(10);

su('admin');
global $tester;
$repo = $tester->loadModel('repo');
$repoTest = new repoModelTest();
r($repo->getRepoUsers(1))        && p('admin') && e('admin');
r($repo->getRepoUsers(0))        && p()        && e('0');
r($repo->getRepoUsers(3))        && p('test2') && e('test2');
r($repo->getRepoUsers(4))        && p('test3') && e('test3');
r($repoTest->getRepoUsersCountTest(4)) && p()  && e('1');
