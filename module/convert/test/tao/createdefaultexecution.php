#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 convertTao::createDefaultExecution();
timeout=0
cid=0

- 测试正常项目创建默认执行 @1
- 测试不同项目创建默认执行 @1
- 测试包含团队成员的项目创建 @1
- 测试项目不存在的情况处理 @0
- 测试空角色参数的项目创建 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 准备测试数据
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('测试项目1,测试项目2,测试项目3,测试项目4,测试项目5');
$project->code->range('project001,project002,project003,project004,project005');
$project->desc->range('项目描述1,项目描述2,项目描述3,项目描述4,项目描述5');
$project->type->range('project{5}');
$project->status->range('wait,doing,done,closed,suspended');
$project->begin->range('2024-01-01,2024-02-01,2024-03-01,2024-04-01,2024-05-01');
$project->end->range('2024-12-31,2024-12-31,2024-12-31,2024-12-31,2024-12-31');
$project->PM->range('admin,admin,user1,user1,user2');
$project->openedBy->range('admin,admin,admin,user1,user1');
$project->openedDate->range('2024-01-01 00:00:00{5}');
$project->openedVersion->range('20.1{5}');
$project->deleted->range('0{5}');
$project->gen(5);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->deleted->range('0{10}');
$user->gen(10);

$company = zenData('company');
$company->id->range('1');
$company->name->range('测试公司');
$company->gen(1);

zenData('lang')->gen(0);
zenData('team')->gen(0);
zenData('action')->gen(0);
zenData('doclib')->gen(0);

su('admin');

$convertTest = new convertTest();

r($convertTest->createDefaultExecutionTest(1001, 1, array())) && p() && e('1'); // 测试正常项目创建默认执行
r($convertTest->createDefaultExecutionTest(1002, 2, array())) && p() && e('1'); // 测试不同项目创建默认执行
r($convertTest->createDefaultExecutionTest(1003, 3, array(1003 => array('user1', 'user2')))) && p() && e('1'); // 测试包含团队成员的项目创建
r($convertTest->createDefaultExecutionTest(1004, 999, array())) && p() && e('0'); // 测试项目不存在的情况处理
r($convertTest->createDefaultExecutionTest(1005, 4, array())) && p() && e('1'); // 测试空角色参数的项目创建