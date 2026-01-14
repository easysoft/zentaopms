#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getGitlabProjects();
timeout=0
cid=18061

- 执行repoTest模块的getGitlabProjectsTest方法，参数是1, ''  @14
- 执行repoTest模块的getGitlabProjectsTest方法，参数是1, 'IS_DEVELOPER'  @14
- 执行repoTest模块的getGitlabProjectsTest方法，参数是1, 'ALL'  @14
- 执行repoTest模块的getGitlabProjectsTest方法，参数是999, ''  @0
- 执行repoTest模块的getGitlabProjectsTest方法，参数是0, ''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(5);
zenData('user')->gen(10);
zenData('repo')->loadYaml('repo')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(5);

su('admin');
$repoTest = new repoModelTest();

r(count($repoTest->getGitlabProjectsTest(1, ''))) && p() && e('14');
r(count($repoTest->getGitlabProjectsTest(1, 'IS_DEVELOPER'))) && p() && e('14');
r(count($repoTest->getGitlabProjectsTest(1, 'ALL'))) && p() && e('14');
r(count($repoTest->getGitlabProjectsTest(999, ''))) && p() && e('0');
r(count($repoTest->getGitlabProjectsTest(0, ''))) && p() && e('0');