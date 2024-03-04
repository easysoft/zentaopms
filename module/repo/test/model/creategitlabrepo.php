#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel::createGitlabRepo();
timeout=0
cid=1

- 创建gitlab远程版本库 @1
- namesapce为空时第namespace条的0属性 @不能为空字符

*/

zdTable('pipeline')->gen(5);

$repoTest = new repoTest();

$repo = new stdclass();
$repo->product      = '1,2';
$repo->projects     = '3,4';
$repo->name         = 'abc&&';
$repo->serviceHost  = 1;
$repo->path         = 'unit_test_project17';
$repo->desc         = 'unit_test_project desc';
$repo->namespace    = 1;
$repo->SCM          = 'Gitlab';
$repo->acl          = '{"acl":"open","groups":[""],"users":[""]}';

$_SERVER['REQUEST_URI'] = 'http://unittest/';

$repo->name = 'unitTestProject17';
$result = $repoTest->createGitlabRepoTest($repo, $repo->namespace);
if(isset($result->id)) $result = true;
if(!empty($result->message->name[0]) and $result->message->name[0] == '已经被使用') $result = true;
r($result) && p() && e('1');         //创建gitlab远程版本库

$result = $repoTest->createGitlabRepoTest($repo, 0);
r($result->message) && p('namespace:0') && e('不能为空字符'); //namesapce为空时