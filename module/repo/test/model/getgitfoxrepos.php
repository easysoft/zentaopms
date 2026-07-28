#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->getGitFoxRepos();
timeout=0
cid=0

- 正常调用返回数组 >> 1
- 返回结果包含未删除且非importing状态的repo1 >> 1
- 返回结果包含repo2 >> 1
- 返回结果包含repo3 >> 1
- importing状态的repo4不在结果中 >> 1
- 已删除的repo5不在结果中 >> 1
- 返回数组数量 >= 3 >> 1

*/

$repoTable = zenData('ops_repo');
$repoTable->id->range('1-5');
$repoTable->spaceID->range('1');
$repoTable->product->range('1');
$repoTable->name->range('repo1,repo2,repo3,repo4,repo5');
$repoTable->scmType->range('git');
$repoTable->gitUID->range('uid1,uid2,uid3,uid4,uid5');
$repoTable->status->range('active{3},importing,active');
$repoTable->deleted->range('0{3},0,1');
$repoTable->acl->range('open');
$repoTable->gen(5);

$repoTest = new repoModelTest();
r($repoTest->getGitFoxReposTest()) && p('1')     && e('repo1');
r($repoTest->getGitFoxReposTest()) && p('2')     && e('repo2');
r($repoTest->getGitFoxReposTest()) && p('3')     && e('repo3');
r($repoTest->getGitFoxReposTest()) && p('4,5')   && e('~~,~~');
r($repoTest->getGitFoxReposTest()) && p('1,2,3') && e('repo1,repo2,repo3');
