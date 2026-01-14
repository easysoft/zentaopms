#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->code->range('1-5')->prefix('code');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220110 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220220 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$burn = zenData('burn');
$burn->execution->range('3{5},4{5},5{5}');
$burn->date->range('20220111 000000:1D')->type('timestamp')->format('YY/MM/DD');
$burn->estimate->range('94.3,56.3,55.3,37.8,33.8');
$burn->left->range('95.3,68.5,73.9,40.2,36,3');
$burn->consumed->range('20.1,33.4,41,56.55,59.55');
$burn->storyPoint->range('0,16.5,16,11.5,9');
$burn->gen(15);

$task = zenData('task');
$task->id->range('1-10');
$task->execution->range('3');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

/**

title=测试executionModel->getBurnDataFlotTest();
timeout=0
cid=0

- 按剩余工时查看敏捷执行查询统计属性value @95.30
- 按剩余工时查看瀑布执行查询统计属性value @3.00
- 按剩余工时查看看板执行查询统计属性value @36.00
- 按计划工时查看敏捷执行查询统计属性value @94.30
- 按计划工时查看瀑布执行查询统计属性value @94.30
- 按计划工时查看看板执行查询统计属性value @94.30
- 按故事点查看敏捷执行查询统计属性value @0.00
- 按故事点查看瀑布执行查询统计属性value @0.00
- 按故事点查看看板执行查询统计属性value @0.00
- 不展示延期按剩余工时查看敏捷执行查询统计属性value @null
- 不展示延期按剩余工时查看瀑布执行查询统计属性value @null
- 不展示延期按剩余工时查看看板执行查询统计属性value @null
- 不展示延期按计划工时查看敏捷执行查询统计属性value @null
- 不展示延期按计划工时查看瀑布执行查询统计属性value @null
- 不展示延期按计划工时查看看板执行查询统计属性value @null
- 不展示延期按故事点查看敏捷执行查询统计属性value @null
- 不展示延期按故事点查看瀑布执行查询统计属性value @null
- 不展示延期按故事点查看看板执行查询统计属性value @null
- 按剩余工时查看敏捷执行查询统计属性value @95.30
- 按剩余工时查看瀑布执行查询统计属性value @3.00
- 按剩余工时查看看板执行查询统计属性value @36.00
- 按计划工时查看敏捷执行查询统计属性value @94.30
- 按计划工时查看瀑布执行查询统计属性value @94.30
- 按计划工时查看看板执行查询统计属性value @94.30
- 按故事点查看敏捷执行查询统计属性value @0.00
- 按故事点查看瀑布执行查询统计属性value @0.00
- 按故事点查看看板执行查询统计属性value @0.00

*/

$executionIDList = array(3, 4, 5);
$burnByList      = array('left', 'estimate', 'storyPoint');
$showDelay       = array(false, true);

$execution = new executionModelTest();
r(current($execution->getBurnDataFlotTest($executionIDList[0], $burnByList[0], $showDelay[0])))                             && p('value') && e('95.30'); // 按剩余工时查看敏捷执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[1], $burnByList[0], $showDelay[0])))                             && p('value') && e('3.00');  // 按剩余工时查看瀑布执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[2], $burnByList[0], $showDelay[0])))                             && p('value') && e('36.00'); // 按剩余工时查看看板执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[0], $burnByList[1], $showDelay[0])))                             && p('value') && e('94.30'); // 按计划工时查看敏捷执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[1], $burnByList[1], $showDelay[0])))                             && p('value') && e('94.30'); // 按计划工时查看瀑布执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[2], $burnByList[1], $showDelay[0])))                             && p('value') && e('94.30'); // 按计划工时查看看板执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[0], $burnByList[2], $showDelay[0])))                             && p('value') && e('0.00');  // 按故事点查看敏捷执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[1], $burnByList[2], $showDelay[0])))                             && p('value') && e('0.00');  // 按故事点查看瀑布执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[2], $burnByList[2], $showDelay[0])))                             && p('value') && e('0.00');  // 按故事点查看看板执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[0], $burnByList[0], $showDelay[1])))                             && p('value') && e('null');  // 不展示延期按剩余工时查看敏捷执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[1], $burnByList[0], $showDelay[1])))                             && p('value') && e('null');  // 不展示延期按剩余工时查看瀑布执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[2], $burnByList[0], $showDelay[1])))                             && p('value') && e('null');  // 不展示延期按剩余工时查看看板执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[0], $burnByList[1], $showDelay[1])))                             && p('value') && e('null');  // 不展示延期按计划工时查看敏捷执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[1], $burnByList[1], $showDelay[1])))                             && p('value') && e('null');  // 不展示延期按计划工时查看瀑布执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[2], $burnByList[1], $showDelay[1])))                             && p('value') && e('null');  // 不展示延期按计划工时查看看板执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[0], $burnByList[2], $showDelay[1])))                             && p('value') && e('null');  // 不展示延期按故事点查看敏捷执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[1], $burnByList[2], $showDelay[1])))                             && p('value') && e('null');  // 不展示延期按故事点查看瀑布执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[2], $burnByList[2], $showDelay[1])))                             && p('value') && e('null');  // 不展示延期按故事点查看看板执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[0], $burnByList[0], $showDelay[0], '2022-01-07', '2022-01-17'))) && p('value') && e('95.30'); // 按剩余工时查看敏捷执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[1], $burnByList[0], $showDelay[0], '2022-01-07', '2022-01-17'))) && p('value') && e('3.00');  // 按剩余工时查看瀑布执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[2], $burnByList[0], $showDelay[0], '2022-01-07', '2022-01-17'))) && p('value') && e('36.00'); // 按剩余工时查看看板执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[0], $burnByList[1], $showDelay[0], '2022-01-07', '2022-01-17'))) && p('value') && e('94.30'); // 按计划工时查看敏捷执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[1], $burnByList[1], $showDelay[0], '2022-01-07', '2022-01-17'))) && p('value') && e('94.30'); // 按计划工时查看瀑布执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[2], $burnByList[1], $showDelay[0], '2022-01-07', '2022-01-17'))) && p('value') && e('94.30'); // 按计划工时查看看板执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[0], $burnByList[2], $showDelay[0], '2022-01-07', '2022-01-17'))) && p('value') && e('0.00');  // 按故事点查看敏捷执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[1], $burnByList[2], $showDelay[0], '2022-01-07', '2022-01-17'))) && p('value') && e('0.00');  // 按故事点查看瀑布执行查询统计
r(current($execution->getBurnDataFlotTest($executionIDList[2], $burnByList[2], $showDelay[0], '2022-01-07', '2022-01-17'))) && p('value') && e('0.00');  // 按故事点查看看板执行查询统计
