#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel::createRepo();
timeout=0
cid=1

- 使用空的名字创建repo群组 @return false
- 使用空的群组URL创建repo群组 @return false
- 使用错误repoID创建群组 @0
- 通过repoID,projectID,分支对象正确创建GitLab分支 @1
- 使用重复的分支信息创建分支第path条的0属性 @已经被使用

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

r($repoTest->createRepoTest($repo)) && p('name') && e('名称应该只包含字母数字，破折号，下划线和点。'); //使用不符合规则的名字创建repo群组

$repo->name = 'unitTestProject17';
$result = $repoTest->createRepoTest($repo);
if(is_int($result)) $result = true;
if(!empty($result->message->name[0]) and $result->message->name[0] == '已经被使用') $result = true;
r($result) && p() && e('1');         //通过repoID,projectID,分支对象正确创建GitLab分支

