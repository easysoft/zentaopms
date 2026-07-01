#!/usr/bin/env php
<?php
/**

title=测试executionModel->syncNoMultipleSprint();
timeout=0
cid=16371

- 同步没有执行的项目
 - 属性id @5
 - 属性name @项目2
 - 属性project @2
 - 属性multiple @0
- 同步错误的项目 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
$execution->id->range('201-205');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->code->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,201,201,202');
$execution->project->range('0,0,201,201,202');
$execution->grade->range('2{2},1{3}');
$execution->multiple->range("`0`");
$execution->path->range('201,202,`201,203`,`201,204`,`202,205`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('202-205');
$projectproduct->product->range('1-3');
$projectproduct->branch->range('1,0');
$projectproduct->gen(5);

su('admin');

$projectIDList = array(202, 15);

$execution = new executionModelTest();
r($execution->syncNoMultipleSprintTest($projectIDList[0])) && p('id,name,project,multiple') && e('205,项目2,202,0'); // 同步没有执行的项目
r($execution->syncNoMultipleSprintTest($projectIDList[1])) && p()                           && e('0');           // 同步错误的项目
