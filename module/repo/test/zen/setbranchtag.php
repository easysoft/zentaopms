#!/usr/bin/env php
<?php

/**

title=测试 repoZen::setBranchTag();
timeout=0
cid=0

- 执行repoZenTest模块的setBranchTagTest方法，参数是$gitRepo, 'master'  @master
- 执行repoZenTest模块的setBranchTagTest方法，参数是$gitlabRepo, 'develop'  @develop
- 执行repoZenTest模块的setBranchTagTest方法，参数是$svnRepo, 'main'  @main
- 执行repoZenTest模块的setBranchTagTest方法，参数是$gitRepo, ''  @master
- 执行repoZenTest模块的setBranchTagTest方法，参数是$gitRepo, 'invalid_branch'  @master

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen_setbranchtag.unittest.class.php';

zenData('repo');
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('Test Repo{1-10}');
$table->SCM->range('Git{5},Gitlab{3},Subversion{2}');
$table->path->range('/test/path{1-10}');
$table->product->range('1-5');
$table->gen(10);

su('admin');

$repoZenTest = new repoZenSetBranchTagTest();

// 创建测试用的repo对象
$gitRepo = new stdClass();
$gitRepo->id = 1;
$gitRepo->SCM = 'Git';
$gitRepo->name = 'Test Git Repo';

$gitlabRepo = new stdClass();
$gitlabRepo->id = 2;
$gitlabRepo->SCM = 'Gitlab';
$gitlabRepo->name = 'Test Gitlab Repo';

$svnRepo = new stdClass();
$svnRepo->id = 3;
$svnRepo->SCM = 'Subversion';
$svnRepo->name = 'Test SVN Repo';

r($repoZenTest->setBranchTagTest($gitRepo, 'master')) && p('0') && e('master');
r($repoZenTest->setBranchTagTest($gitlabRepo, 'develop')) && p('0') && e('develop');
r($repoZenTest->setBranchTagTest($svnRepo, 'main')) && p('0') && e('main');
r($repoZenTest->setBranchTagTest($gitRepo, '')) && p('0') && e('master');
r($repoZenTest->setBranchTagTest($gitRepo, 'invalid_branch')) && p('0') && e('master');