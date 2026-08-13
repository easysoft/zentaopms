#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->createGitlabRepo();
timeout=0
cid=18037

- namespace=1 调用缺失 apiCreateProject 方法属性namespace,status @1,exception
- namespace=0 调用缺失 apiCreateProject 方法属性namespace,status @0,exception
- 空 name 场景保留输入属性name,status @,exception
- 空 path 场景保留输入属性path,status @,exception
- 负 namespace 调用缺失 apiCreateProject 方法属性namespace,error @-1,the module gitlab has no apiCreateProject method

*/

$repoTest = new repoModelTest();
$_SERVER['REQUEST_URI'] = 'http://unittest/';

$repo = new stdclass();
$repo->product     = '1';
$repo->name        = 'testproject';
$repo->serviceHost = 1;
$repo->path        = 'test-project';
$repo->desc        = 'desc';
$repo->SCM         = 'Gitlab';
$repo->acl         = 'open';

r($repoTest->createGitlabRepoTest($repo, 1)) && p('status') && e('exception');
r($repoTest->createGitlabRepoTest($repo, 0)) && p('status') && e('exception');

$emptyRepo = clone $repo; $emptyRepo->name = '';
r($repoTest->createGitlabRepoTest($emptyRepo, 1)) && p('status') && e('exception');

$emptyPath = clone $repo; $emptyPath->path = '';
r($repoTest->createGitlabRepoTest($emptyPath, 1)) && p('status') && e('exception');

r($repoTest->createGitlabRepoTest($repo, -1)) && p('status') && e('exception');
