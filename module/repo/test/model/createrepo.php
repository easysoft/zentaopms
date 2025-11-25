#!/usr/bin/env php
<?php

/**

title=测试 repoModel::createRepo();
timeout=0
cid=18039

- 执行repoTest模块的createRepoTest方法，参数是$repo1 属性name @名称必须以字母或 _ 开头，只包含字母数字，破折号，下划线和点。
- 执行repoTest模块的createRepoTest方法，参数是$repo2 属性name @名称必须以字母或 _ 开头，只包含字母数字，破折号，下划线和点。
- 执行repoTest模块的createRepoTest方法，参数是$repo3 属性name @名称必须以字母或 _ 开头，只包含字母数字，破折号，下划线和点。
- 执行$result4 @1
- 执行$result5 @已经被使用

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 准备测试数据
$table = zenData('pipeline');
$table->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoTest();

// 设置通用测试数据
$baseRepo = new stdclass();
$baseRepo->product      = '1,2';
$baseRepo->projects     = '3,4';
$baseRepo->serviceHost  = 1;
$baseRepo->path         = 'unit_test_project_' . time();
$baseRepo->desc         = 'unit test repo description';
$baseRepo->namespace    = '1';
$baseRepo->SCM          = 'Gitlab';
$baseRepo->acl          = '{"acl":"open","groups":[""],"users":[""]}';

$_SERVER['REQUEST_URI'] = 'http://unittest/';

// 测试步骤1：使用不符合规则的名字创建repo
$repo1 = clone $baseRepo;
$repo1->name = 'abc&&';
r($repoTest->createRepoTest($repo1)) && p('name') && e('名称必须以字母或 _ 开头，只包含字母数字，破折号，下划线和点。');

// 测试步骤2：使用空名称创建repo
$repo2 = clone $baseRepo;
$repo2->name = '';
r($repoTest->createRepoTest($repo2)) && p('name') && e('名称必须以字母或 _ 开头，只包含字母数字，破折号，下划线和点。');

// 测试步骤3：使用数字开头的名称创建repo
$repo3 = clone $baseRepo;
$repo3->name = '123invalid';
r($repoTest->createRepoTest($repo3)) && p('name') && e('名称必须以字母或 _ 开头，只包含字母数字，破折号，下划线和点。');

// 测试步骤4：使用正确的数据创建版本库
$repo4 = clone $baseRepo;
$repo4->name = 'validRepoName_' . time();
$result4 = $repoTest->createRepoTest($repo4);
if(is_int($result4)) $result4 = 1;
if(!empty($result4['name'][0]) and $result4['name'][0] == '已经被使用') $result4 = 1;
r($result4) && p() && e('1');

// 测试步骤5：使用已存在的名称创建repo（重复前面成功的名称）
$repo5 = clone $baseRepo;
$repo5->name = $repo4->name;
$result5 = $repoTest->createRepoTest($repo5);
if(isset($result5['name'][0]) and $result5['name'][0] == '已经被使用') $result5 = '已经被使用';
r($result5) && p() && e('已经被使用');