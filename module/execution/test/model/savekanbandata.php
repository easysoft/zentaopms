#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
/**

title=测试executionModel->saveKanbanData();
timeout=0
cid=16362

- 保存看板1的空数据第story条的0属性 @3
- 保存看板1的空数据 @empty
- 保存看板1的看板数据第story条的0属性 @~~
- 保存看板1的看板数据第story条的0属性 @~~
- 保存看板1的看板数据第story条的0属性 @~~
- 保存看板1的看板数据第story条的0属性 @~~

*/

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$projectstory = zenData('projectstory');
$projectstory->project->range('5');
$projectstory->product->range('1');
$projectstory->story->range('3,5,7');
$projectstory->gen(3);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$stroy = zenData('story');
$stroy->id->range('3,5,7');
$stroy->title->range('1-3')->prefix('需求');
$stroy->type->range('story');
$stroy->product->range('1');
$stroy->status->range('active');
$stroy->gen(3);
$stroy = zenData('story');

su('admin');

$execution = new executionTest();

$executionIdList = range(1, 5);

r($execution->saveKanbanDataTest($executionIdList[4]))       && p('story:0') && e('3');     // 保存看板1的空数据
r($execution->saveKanbanDataTest($executionIdList[4], true)) && p('')        && e('empty'); // 保存看板1的空数据
r($execution->saveKanbanDataTest($executionIdList[0]))       && p('story:0') && e('~~');    // 保存看板1的看板数据
r($execution->saveKanbanDataTest($executionIdList[1]))       && p('story:0') && e('~~');    // 保存看板1的看板数据
r($execution->saveKanbanDataTest($executionIdList[2]))       && p('story:0') && e('~~');    // 保存看板1的看板数据
r($execution->saveKanbanDataTest($executionIdList[3]))       && p('story:0') && e('~~');    // 保存看板1的看板数据
