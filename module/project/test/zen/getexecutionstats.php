#!/usr/bin/env php
<?php

/**

title=测试 loadModel->getExecutionStats()
timeout=0
cid=17943

- 查看获取到的执行列表数量 @1
- 查看获取到的执行详情
 - 第0条的name属性 @迭代2
 - 第0条的id属性 @4
 - 第0条的type属性 @stage
 - 第0条的code属性 @program4
- 传入productID，查看获取到的执行列表数量 @0
- 传入productID，查看获取到的执行列表详情 @0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

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
$task->consumed->range('1-10');
$task->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

global $tester;
$zen  = initReference('project');
$func = $zen->getMethod('getExecutionStats');
$tester->app->rawModule = 'project';
$tester->app->rawMethod = 'execution';
$tester->app->loadClass('pager', true);
$pager = new pager(10, 10, 1);
$result = $func->invokeArgs($zen->newInstance(), ['all', 1, array(1,2,3), 0, 0, 'id_desc', $pager]);

r($result) && p('0:name,id,type,code') && e('迭代2,4,stage,program4'); // 查看获取到的执行详情

$result = $func->invokeArgs($zen->newInstance(), ['all', 1, array(1,2,3), 1, 0, 'id_desc', $pager]);

r(count($result)) && p('') && e('0'); // 传入productID，查看获取到的执行列表数量
r($result)        && p('') && e('0'); // 传入productID，查看获取到的执行列表详情