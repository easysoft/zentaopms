#!/usr/bin/env php
<?php

/**

title=测试 designZen::setMenu();
timeout=0
cid=0

- 测试项目不存在的情况属性count @0
- 测试 waterfall 项目且 type 为空的情况
 - 属性count @5
 - 属性hasAll @1
 - 属性hasHlds @1
 - 属性hasDds @1
- 测试 waterfall 项目且 type 为 HLDS 的情况
 - 属性count @5
 - 属性hasAll @1
 - 属性hasHlds @1
- 测试 ipd 项目的情况
 - 属性count @5
 - 属性hasAll @1
- 测试 waterfallplus 项目的情况
 - 属性count @5
 - 属性hasAll @1
 - 属性hasHlds @1
- 测试项目模型为其他类型的情况属性count @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->project->range('0');
$project->isTpl->range('0');
$project->model->range('waterfall,waterfall,ipd,waterfallplus,scrum,kanban,agile,sprint,stage,build');
$project->type->range('project');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->begin->range('`2024-01-01`');
$project->end->range('`2024-12-31`');
$project->status->range('wait');
$project->acl->range('open');
$project->order->range('1-10');
$project->deleted->range('0');
$project->gen(10);

zenData('user')->gen(3);

su('admin');

$designTest = new designZenTest();

r($designTest->setMenuTest(999, 0, '')) && p('count') && e('0'); // 测试项目不存在的情况
r($designTest->setMenuTest(1, 1, '')) && p('count,hasAll,hasHlds,hasDds') && e('5,1,1,1'); // 测试 waterfall 项目且 type 为空的情况
r($designTest->setMenuTest(2, 1, 'hlds')) && p('count,hasAll,hasHlds') && e('5,1,1'); // 测试 waterfall 项目且 type 为 HLDS 的情况
r($designTest->setMenuTest(3, 1, '')) && p('count,hasAll') && e('5,1'); // 测试 ipd 项目的情况
r($designTest->setMenuTest(4, 1, '')) && p('count,hasAll,hasHlds') && e('5,1,1'); // 测试 waterfallplus 项目的情况
r($designTest->setMenuTest(5, 0, '')) && p('count') && e('0'); // 测试项目模型为其他类型的情况