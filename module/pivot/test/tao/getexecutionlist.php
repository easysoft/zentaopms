#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getExecutionList();
timeout=0
cid=17441

- 执行pivotTest模块的getExecutionListTest方法，参数是'', '', array  @5
- 执行pivotTest模块的getExecutionListTest方法，参数是'2024-06-01', '2024-06-29', array  @0
- 执行pivotTest模块的getExecutionListTest方法，参数是'2024-07-01', '2024-07-31', array  @2
- 执行pivotTest模块的getExecutionListTest方法，参数是'', '', array  @2
- 执行pivotTest模块的getExecutionListTest方法，参数是'', '', array
 - 属性projectID @5
 - 属性executionID @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$task = zenData('task');
$task->id->range('1-20');
$task->project->range('1{4},2{4},3{4},4{4},5{4}');
$task->execution->range('6{4},7{4},8{4},9{4},10{4}');
$task->parent->range('0');
$task->status->range('wait{4},doing{6},done{10}');
$task->estimate->range('1{4},2{4},4{4},8{4},16{4}');
$task->consumed->range('0.5{4},1{4},2{4},4{4},8{4}');
$task->deleted->range('0');
$task->gen(20);

$project = zenData('project');
$project->id->range('1-15');
$project->project->range('0{5},1{2},2{2},3{2},4{2},5{2}');
$project->type->range('project{5},sprint{10}');
$project->name->range('项目1,项目2,项目3,项目4,项目5,执行6,执行7,执行8,执行9,执行10,执行11,执行12,执行13,执行14,执行15');
$project->begin->range('`2024-01-01`');
$project->end->range('`2024-06-30`{4},`2024-07-31`{3},`2024-08-31`{3},`2024-12-31`{5}');
$project->realBegan->range('`2024-06-01`{4},`2024-07-01`{3},`2024-08-01`{3},`2024-01-01`{5}');
$project->realEnd->range('`2024-06-30`{4},`2024-07-31`{3},`2024-08-31`{3},`2024-12-31`{5}');
$project->status->range('doing{5},closed{10}');
$project->multiple->range('1');
$project->deleted->range('0');
$project->gen(15);

su('admin');

$pivotTest = new pivotTaoTest();

r(count($pivotTest->getExecutionListTest('', '', array()))) && p() && e('5');
r(count($pivotTest->getExecutionListTest('2024-06-01', '2024-06-29', array()))) && p() && e('0');
r(count($pivotTest->getExecutionListTest('2024-07-01', '2024-07-31', array()))) && p() && e('2');
r(count($pivotTest->getExecutionListTest('', '', array(7, 8)))) && p() && e('2');
r($pivotTest->getExecutionListTest('', '', array())[0]) && p('projectID,executionID') && e('5,10');