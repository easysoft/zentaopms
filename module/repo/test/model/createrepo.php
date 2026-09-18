#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->createRepo();
timeout=0
cid=18039

- 非法名称返回名称校验错误 @error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。
- 空名称返回名称校验错误 @error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。
- 数字开头名称返回名称校验错误 @error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。
- 真实测试空间创建成功 @1
- 合法名称调用真实 GitFox 接口创建仓库成功属性name,status @validrepo,success

*/

$repoTest = new repoModelTest();

$_SERVER['REQUEST_URI'] = 'http://unittest/';
zenData('user')->gen(10);
$repoTest->seedGitFoxEntry();

/* 通过真实 GitFox 接口创建独立测试空间，避免依赖已有数据。 */
$space = $repoTest->createGitFoxSpaceTest();

$baseRepo = new stdclass();
$baseRepo->product  = '1';
$baseRepo->space    = (int)$space->id;
$baseRepo->SCM      = 'Gitlab';
$baseRepo->acl      = 'open';
$baseRepo->desc     = 'repo unit test';

$repo1 = clone $baseRepo; $repo1->name = 'abc&&';
r($repoTest->createRepoTest($repo1)) && p('status,error') && e('error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。');

$repo2 = clone $baseRepo; $repo2->name = '';
r($repoTest->createRepoTest($repo2)) && p('status,error') && e('error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。');

$repo3 = clone $baseRepo; $repo3->name = '123invalid';
r($repoTest->createRepoTest($repo3)) && p('status,error') && e('error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。');

r($repoTest->hasGitFoxSpaceTest($space)) && p() && e('1');

$repo4 = clone $baseRepo; $repo4->name = 'validrepo';
$createdRepo4 = $repoTest->createRepoTest($repo4);
r($createdRepo4) && p('name,status') && e('validrepo,success');

/* 清理本次测试创建的仓库数据，避免污染 GitFox 服务和后续测试。 */
$repoTest->deleteGitFoxRepoTest((int)$createdRepo4->id);
$repoTest->deleteGitFoxSpaceTest((int)$space->id);
