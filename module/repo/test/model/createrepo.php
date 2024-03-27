#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel::createRepo();
timeout=0
cid=1

- 使用不符合规则的名字创建repo属性name @名称应该只包含字母数字，破折号，下划线和点。
- 通过正确数据创建版本库 @1

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

r($repoTest->createRepoTest($repo)) && p('name') && e('名称应该只包含字母数字，破折号，下划线和点。'); //使用不符合规则的名字创建repo

$repo->name = 'unitTestProject17';
$result = $repoTest->createRepoTest($repo);
if(is_int($result)) $result = true;
if(!empty($result['name'][0]) and $result['name'][0] == '已经被使用') $result = true;
r($result) && p() && e('1');         //通过正确数据创建版本库