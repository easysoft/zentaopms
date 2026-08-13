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
- 小写合法名称调用真实 GitFox 接口属性name,status @validrepo,false
- 含连字符合法名称调用真实 GitFox 接口属性name,status @repo-valid-1,false

*/

$repoTest = new repoModelTest();

$_SERVER['REQUEST_URI'] = 'http://unittest/';
$repoTest->seedGitFoxEntry();

$baseRepo = new stdclass();
$baseRepo->product  = '1';
$baseRepo->space    = 1;
$baseRepo->SCM      = 'Gitlab';
$baseRepo->acl      = 'open';
$baseRepo->desc     = 'repo unit test';

$repo1 = clone $baseRepo; $repo1->name = 'abc&&';
r($repoTest->createRepoTest($repo1)) && p('status,error') && e('error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。');

$repo2 = clone $baseRepo; $repo2->name = '';
r($repoTest->createRepoTest($repo2)) && p('status,error') && e('error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。');

$repo3 = clone $baseRepo; $repo3->name = '123invalid';
r($repoTest->createRepoTest($repo3)) && p('status,error') && e('error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。');

$repo4 = clone $baseRepo; $repo4->name = 'validrepo';
r($repoTest->createRepoTest($repo4)) && p('status') && e('error');

$repo5 = clone $baseRepo; $repo5->name = 'repo-valid-1';
r($repoTest->createRepoTest($repo5)) && p('status') && e('error');
