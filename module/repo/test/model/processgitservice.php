#!/usr/bin/env php
<?php

/**

title=测试 repoModel::processGitService();
timeout=0
cid=18089

- 执行repo模块的processGitServiceConfigStatusTest方法，参数是1  @1
- 执行repo模块的processGitServiceConfigStatusTest方法，参数是4, 'codePath'  @1
- 执行repo模块的processGitServiceConfigStatusTest方法，参数是2  @1
- 执行repo模块的processGitServiceConfigStatusTest方法，参数是1, 'apiPath'  @1
- 执行repo模块的processGitServiceConfigStatusTest方法，参数是4, 'emptyHost'  @1
- 执行repo模块的processGitServiceConfigStatusTest方法，参数是1, 'invalid'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$repoTable = zenData('ops_repo');
$repoTable->id->range('1,2,4');
$repoTable->spaceID->range('1{3}');
$repoTable->product->range('1{3}');
$repoTable->name->range('repo1,repo2,repo4');
$repoTable->scmType->range('git');
$repoTable->gitUID->range('uid1,uid2,uid4');
$repoTable->acl->range('private');
$repoTable->status->range('active');
$repoTable->deleted->range('0');
$repoTable->gen(3);

zenData('entry')->loadYaml('entry')->gen(1);

su('admin');

$repo = new repoModelTest();
$repo->setGitFoxRepoCache(1, (object)array('gitURL' => 'http://gitfox.test/repo1.git', 'path' => 'repo1', 'importing' => false));
$repo->setGitFoxRepoCache(2, (object)array('gitURL' => 'http://gitfox.test/repo2.git', 'path' => 'repo2', 'importing' => false));
$repo->setGitFoxRepoCache(4, (object)array('gitURL' => 'http://gitfox.test/repo4.git', 'path' => 'repo4', 'importing' => false));

r($repo->processGitServiceConfigStatusTest(1)) && p() && e('1');
r($repo->processGitServiceConfigStatusTest(4, 'codePath')) && p() && e('1');
r($repo->processGitServiceConfigStatusTest(2)) && p() && e('1');
r($repo->processGitServiceConfigStatusTest(1, 'apiPath')) && p() && e('1');
r($repo->processGitServiceConfigStatusTest(4, 'emptyHost')) && p() && e('1');
r($repo->processGitServiceConfigStatusTest(1, 'invalid')) && p() && e('1');