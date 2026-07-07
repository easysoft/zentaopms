#!/usr/bin/env php
<?php

/**

title=测试 repoModel::createGitlabRepo();
timeout=0
cid=18037

- 执行$result @object
- 执行$result @object
- 执行$result @false
- 执行$result @false
- 执行$result @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(5);

su('admin');

$repoTest = new repoModelTest();

$_SERVER['REQUEST_URI'] = 'http://unittest/';

$normalizeResult = static function($result): string
{
    if($result === false) return 'false';

    if(is_object($result))
    {
        if(isset($result->id) || isset($result->message) || isset($result->error) || isset($result->path)) return 'object';
        return 'unexpected_object';
    }

    return (string)$result;
};

// 测试步骤1：当前环境下返回远端错误结构或成功对象
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

$result = $normalizeResult($repoTest->createGitlabRepoTest($repo, $repo->namespace));
r($result) && p() && e('object');

// 测试步骤2：边界值测试，命名空间为0的情况
$result = $normalizeResult($repoTest->createGitlabRepoTest($repo, 0));
r($result) && p() && e('object');

// 测试步骤3：无效输入测试，repo对象缺少name属性的情况
$emptyRepo = new stdclass();
$emptyRepo->name = '';
$emptyRepo->serviceHost = 1;
$emptyRepo->desc = '';
$result = $normalizeResult($repoTest->createGitlabRepoTest($emptyRepo, 1));
r($result) && p() && e('false');

// 测试步骤4：项目名称为空字符串测试
$emptyNameRepo = clone $repo;
$emptyNameRepo->name = '';
$emptyNameRepo->path = '';
$result = $normalizeResult($repoTest->createGitlabRepoTest($emptyNameRepo, 1));
r($result) && p() && e('false');

// 测试步骤5：命名空间为负数测试，使用负数的命名空间ID
$negativeNamespaceRepo = clone $repo;
$negativeNamespaceRepo->name = 'testNegativeNamespace';
$negativeNamespaceRepo->path = 'test-negative-namespace';
$result = $normalizeResult($repoTest->createGitlabRepoTest($negativeNamespaceRepo, -1));
r($result) && p() && e('object');
