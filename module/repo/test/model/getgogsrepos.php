#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

global $tester;

/**

title=测试 repoModel->getGogsRepos();
timeout=0
cid=0

- 执行repoTest模块的getGogsReposIsArrayTest方法，参数是$apiRoot  @1
- 执行repoTest模块的getGogsReposFirstFieldTest方法，参数是$apiRoot, 'id'  @1
- 执行repoTest模块的getGogsReposFirstFieldTest方法，参数是$apiRoot, 'full_name'  @1
- 执行repoTest模块的getGogsReposCountTest方法，参数是''  @0
- 执行repoTest模块的getGogsReposCountGreaterThanTest方法，参数是$apiRoot, 0  @1

*/

$repoTest = new repoModelTest();

$apiRoot = 'https://gogsdev.qc.oop.cc/api/v1%s?token=0c37d25758930f24e955dd0307bd37e975e3b457';
r($repoTest->getGogsReposIsArrayTest($apiRoot))                && p() && e('1');
r($repoTest->getGogsReposFirstFieldTest($apiRoot, 'id'))      && p() && e('1');
r($repoTest->getGogsReposFirstFieldTest($apiRoot, 'full_name')) && p() && e('1');
r($repoTest->getGogsReposCountTest(''))                       && p() && e('0');
r($repoTest->getGogsReposCountGreaterThanTest($apiRoot, 0))   && p() && e('1');