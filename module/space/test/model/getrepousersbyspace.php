#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getRepoUsersBySpace();
timeout=0
cid=0

- 查询所有空间的仓库用户并验证结果类型 @1
- 查询有效空间ID=1的仓库用户并验证结果类型 @1
- 查询无效空间ID=9999的仓库用户为空 @0
- 查询空间ID=0的仓库用户(spaceID!=0)并验证结果类型 @1
- 查询有效空间ID=2的仓库用户并验证结果类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(10);
zenData('ops_spaceuser')->gen(10);
zenData('ops_repouser')->gen(5);

su('admin');

$spaceTester = new spaceModelTest();

r(is_array($spaceTester->getRepoUsersBySpaceTest())) && p() && e('1');       // 查询所有空间的仓库用户并验证结果类型
r(is_array($spaceTester->getRepoUsersBySpaceTest(1))) && p() && e('1');      // 查询有效空间ID=1的仓库用户并验证结果类型
r($spaceTester->getRepoUsersBySpaceTest(9999)) && p() && e('0');             // 查询无效空间ID=9999的仓库用户为空
r(is_array($spaceTester->getRepoUsersBySpaceTest(0))) && p() && e('1');      // 查询空间ID=0的仓库用户(spaceID!=0)并验证结果类型
r(is_array($spaceTester->getRepoUsersBySpaceTest(2))) && p() && e('1');      // 查询有效空间ID=2的仓库用户并验证结果类型
