#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

global $tester;

/**

title=测试 repoModel->getGiteaRepos();
timeout=0
cid=0

- 执行repoTest模块的getGiteaReposIsArrayTest方法，参数是$apiRoot  @1
- 执行repoTest模块的getGiteaReposFirstFieldTest方法，参数是$apiRoot, 'id'  @1
- 执行repoTest模块的getGiteaReposFirstFieldTest方法，参数是$apiRoot, 'name'  @1
- 执行repoTest模块的getGiteaReposCountTest方法，参数是''  @0
- 执行repoTest模块的getGiteaReposCountGreaterThanTest方法，参数是$apiRoot, 0  @1

*/

$repoTest = new repoModelTest();

$apiRoot = 'https://giteadev.qc.oop.cc/api/v1%s?token=6149a6013047301b116389d50db5cbf599772082';
r($repoTest->getGiteaReposIsArrayTest($apiRoot))              && p() && e('1');
r($repoTest->getGiteaReposFirstFieldTest($apiRoot, 'id'))    && p() && e('1');
r($repoTest->getGiteaReposFirstFieldTest($apiRoot, 'name'))  && p() && e('1');
r($repoTest->getGiteaReposCountTest(''))                     && p() && e('0');
r($repoTest->getGiteaReposCountGreaterThanTest($apiRoot, 0)) && p() && e('1');