#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildActivateForm();
timeout=0
cid=0

- 执行projectTest模块的buildActivateFormTest方法，参数是$normalProject  @1
- 执行projectTest模块的buildActivateFormTest方法，参数是null  @1
- 执行projectTest模块的buildActivateFormTest方法，参数是$project2  @1
- 执行projectTest模块的buildActivateFormTest方法，参数是$project3  @1
- 执行projectTest模块的buildActivateFormTest方法，参数是$project4  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->begin->range('`2023-01-01`,`2023-02-01`,`2023-03-01`,`2023-04-01`,`2023-05-01`');
$project->end->range('`2023-12-31`,`2023-12-31`,`2023-12-31`,`2023-12-31`,`2023-12-31`');
$project->status->range('wait,doing,suspended,closed,wait');
$project->type->range('project{5}');
$project->acl->range('open{5}');
$project->gen(5);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->deleted->range('0{5}');
$user->gen(5);

su('admin');

$projectTest = new projectTest();

// 测试步骤1：传入正常项目对象
$normalProject = new stdClass();
$normalProject->id = 1;
$normalProject->begin = '2023-01-01';
$normalProject->end = '2023-12-31';
r($projectTest->buildActivateFormTest($normalProject)) && p() && e('1');

// 测试步骤2：传入null项目对象（使用默认值）
r($projectTest->buildActivateFormTest(null)) && p() && e('1');

// 测试步骤3：传入不同日期的项目对象
$project2 = new stdClass();
$project2->id = 2;
$project2->begin = '2024-01-01';
$project2->end = '2024-06-30';
r($projectTest->buildActivateFormTest($project2)) && p() && e('1');

// 测试步骤4：传入长期项目对象
$project3 = new stdClass();
$project3->id = 3;
$project3->begin = '2023-01-01';
$project3->end = '2025-12-31';
r($projectTest->buildActivateFormTest($project3)) && p() && e('1');

// 测试步骤5：传入短期项目对象
$project4 = new stdClass();
$project4->id = 4;
$project4->begin = '2024-01-01';
$project4->end = '2024-01-31';
r($projectTest->buildActivateFormTest($project4)) && p() && e('1');