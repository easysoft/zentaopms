#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3-5');
$task->type->range('test,devel');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->story->range('0');
$task->consumed->range('1-10');
$task->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$related = zenData('projectproduct');
$related->project->range('3-5');
$related->product->range('1-3');
$related->branch->range('1-10');

zenData('branch')->gen(10);
su('admin');

/**

title=测试executionModel->getTasks2ImportedTest();
timeout=0
cid=16344

- 敏捷执行任务查看第1条的name属性 @任务1
- 瀑布执行任务查看第2条的type属性 @devel
- 看板执行任务查看第3条的status属性 @wait
- 敏捷执行任务统计 @4
- 敏捷执行任务统计 @3
- 敏捷执行任务统计 @3

*/

$executionIDList = array('3', '4', '5');
$count         = array('0','1');

$execution = new executionModelTest();
r($execution->getTasks2ImportedTest($executionIDList[0],$count[0])) && p('1:name')   && e('任务1'); // 敏捷执行任务查看
r($execution->getTasks2ImportedTest($executionIDList[1],$count[0])) && p('2:type')   && e('devel'); // 瀑布执行任务查看
r($execution->getTasks2ImportedTest($executionIDList[2],$count[0])) && p('3:status') && e('wait');  // 看板执行任务查看
r($execution->getTasks2ImportedTest($executionIDList[0],$count[1])) && p()           && e('4');     // 敏捷执行任务统计
r($execution->getTasks2ImportedTest($executionIDList[1],$count[1])) && p()           && e('3');     // 敏捷执行任务统计
r($execution->getTasks2ImportedTest($executionIDList[2],$count[1])) && p()           && e('3');     // 敏捷执行任务统计
