#!/usr/bin/env php
<?php

/**

title=测试 repoModel::createGitlabRepo();
timeout=0
cid=18037

- 执行$result @1
- 执行$result->message第namespace条的0属性 @不能为空字符
- 执行$result @false
- 执行$result @false
- 执行$result @negative_namespace_error

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(5);

su('admin');

$repoTest = new repoModelTest();

$_SERVER['REQUEST_URI'] = 'http://unittest/';

// 测试步骤1：正常输入情况，创建有效的GitLab项目
$repo = new stdclass();
$repo->product      = '1,2';
$repo->projects     = '3,4';
$repo->name         = 'unitTestProject17';
$repo->serviceHost  = 1;
$repo->path         = 'unit_test_project17';
$repo->desc         = 'unit_test_project desc';
$repo->namespace    = 1;
$repo->SCM          = 'Gitlab';
$repo->acl          = '{"acl":"open","groups":[""],"users":[""]}';

$result = $repoTest->createGitlabRepoTest($repo, $repo->namespace);
if(isset($result->id)) $result = true;
if(!empty($result->message->name[0]) and $result->message->name[0] == '已经被使用') $result = true;
r($result) && p() && e('1');

// 测试步骤2：边界值测试，命名空间为0的情况
$result = $repoTest->createGitlabRepoTest($repo, 0);
r($result->message) && p('namespace:0') && e('不能为空字符');

// 测试步骤3：无效输入测试，repo对象缺少name属性的情况
$emptyRepo = new stdclass();
$emptyRepo->name = '';
$emptyRepo->serviceHost = 1;
$emptyRepo->desc = '';
$result = $repoTest->createGitlabRepoTest($emptyRepo, 1);
if($result === false) $result = 'false';
r($result) && p() && e('false');

// 测试步骤4：项目名称为空字符串测试
$emptyNameRepo = clone $repo;
$emptyNameRepo->name = '';
$emptyNameRepo->path = '';
$result = $repoTest->createGitlabRepoTest($emptyNameRepo, 1);
if($result === false) $result = 'false';
r($result) && p() && e('false');

// 测试步骤5：命名空间为负数测试，使用负数的命名空间ID
$negativeNamespaceRepo = clone $repo;
$negativeNamespaceRepo->name = 'testNegativeNamespace';
$negativeNamespaceRepo->path = 'test-negative-namespace';
$result = $repoTest->createGitlabRepoTest($negativeNamespaceRepo, -1);
if(!empty($result->message)) $result = 'negative_namespace_error';
if($result === false) $result = 'false';
r($result) && p() && e('negative_namespace_error');