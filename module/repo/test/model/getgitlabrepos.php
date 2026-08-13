#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getGitLabRepos();
timeout=0
cid=0

- 执行repoTest模块的getGitLabReposIsArrayTest方法，参数是$apiRoot  @1
- 执行repoTest模块的getGitLabReposFirstFieldTest方法，参数是$apiRoot, 'id'  @1
- 执行repoTest模块的getGitLabReposFirstFieldTest方法，参数是$apiRoot, 'name'  @1
- 执行repoTest模块的getGitLabReposCountTest方法，参数是''  @0
- 执行repoTest模块的getGitLabReposCountGreaterThanTest方法，参数是$apiRoot, 0  @1

*/

$repoTest = new repoModelTest();

$apiRoot = 'https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w';
r($repoTest->getGitLabReposIsArrayTest($apiRoot))              && p() && e('1');
r($repoTest->getGitLabReposFirstFieldTest($apiRoot, 'id'))    && p() && e('1');
r($repoTest->getGitLabReposFirstFieldTest($apiRoot, 'name'))  && p() && e('1');
r($repoTest->getGitLabReposCountTest(''))                     && p() && e('0');
r($repoTest->getGitLabReposCountGreaterThanTest($apiRoot, 0)) && p() && e('1');