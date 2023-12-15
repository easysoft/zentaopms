#!/usr/bin/env php
<?php

/**

title=测试executionModel->syncNoMultipleSprint();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->code->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->multiple->range("`0`");
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$product = zdTable('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$projectproduct = zdTable('projectproduct');
$projectproduct->project->range('2-5');
$projectproduct->product->range('1-3');
$projectproduct->branch->range('1,0');
$projectproduct->gen(5);

su('admin');

$projectIDList = array(2, 15);

$execution = new executionTest();
r($execution->syncNoMultipleSprintTest($projectIDList[0])) && p() && e('5'); // 同步没有执行的项目
r($execution->syncNoMultipleSprintTest($projectIDList[1])) && p() && e('0'); // 同步错误的项目
