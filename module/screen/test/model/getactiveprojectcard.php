#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getActiveProjectCard();
timeout=0
cid=18233

- 执行screenTest模块的getActiveProjectCardTest方法，参数是'2024', '01' 第0条的count属性 @3
- 执行screenTest模块的getActiveProjectCardTest方法，参数是'2023', '12' 第0条的count属性 @2
- 执行screenTest模块的getActiveProjectCardTest方法，参数是'2025', '01' 第0条的count属性 @0
- 执行screenTest模块的getActiveProjectCardTest方法，参数是'2024', '13' 第0条的count属性 @0
- 执行screenTest模块的getActiveProjectCardTest方法，参数是'0', '0' 第0条的count属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project{8},program{2}');
$project->status->range('wait{2},doing{3},suspended{1},done{2},closed{2}');
$project->deleted->range('0{8},1{2}');
$project->vision->range('[]{8},or{2}');
$project->gen(10);

$action = zenData('action');
$action->id->range('1-15');
$action->objectType->range('project{10},user{3},task{2}');
$action->project->range('1{3},2{3},3{2},4{2},0{5}');
$action->date->range('`2024-01-01 10:00:00`,`2024-01-15 14:30:00`,`2024-01-30 16:45:00`,`2023-12-15 09:20:00`,`2023-12-28 11:10:00`,`2022-05-10 12:00:00`');
$action->actor->range('admin{5},user1{5},test{5}');
$action->action->range('opened{8},edited{4},created{3}');
$action->comment->range('测试备注{15}');
$action->gen(15);

su('admin');

$screenTest = new screenTest();

r($screenTest->getActiveProjectCardTest('2024', '01')) && p('0:count') && e('3');
r($screenTest->getActiveProjectCardTest('2023', '12')) && p('0:count') && e('2');
r($screenTest->getActiveProjectCardTest('2025', '01')) && p('0:count') && e('0');
r($screenTest->getActiveProjectCardTest('2024', '13')) && p('0:count') && e('0');
r($screenTest->getActiveProjectCardTest('0', '0')) && p('0:count') && e('0');