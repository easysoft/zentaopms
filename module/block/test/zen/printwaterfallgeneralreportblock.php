#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printWaterfallGeneralReportBlock();
timeout=0
cid=0

- 测试项目1：有任务、有工时数据、部分任务已完成
 - 属性pv @2000
 - 属性ev @1680
 - 属性ac @240.00
 - 属性sv @-16.00
 - 属性cv @600.00
 - 属性progress @37.5
- 测试项目2：有工时数据但无任务数据
 - 属性pv @0
 - 属性ev @0
 - 属性ac @240.00
 - 属性sv @0
 - 属性cv @-100.00
 - 属性progress @100
- 测试项目3：有工时数据但无任务数据
 - 属性pv @0
 - 属性ev @0
 - 属性ac @240.00
 - 属性sv @0
 - 属性cv @-100.00
 - 属性progress @100
- 测试项目4：有工时数据但无任务数据
 - 属性pv @0
 - 属性ev @0
 - 属性ac @240.00
 - 属性sv @0
 - 属性cv @-100.00
 - 属性progress @100
- 测试项目5：有工时数据但无任务数据
 - 属性pv @0
 - 属性ev @0
 - 属性ac @240.00
 - 属性sv @0
 - 属性cv @-100.00
 - 属性progress @100

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('project')->loadYaml('project', false, 2)->gen(50);

$execution = zenData('project');
$execution->id->range('101-150');
$execution->project->range('1{10},2{10},3{10},4{10},5{10}');
$execution->type->range('stage');
$execution->vision->range('rnd');
$execution->deleted->range('0');
$execution->gen(50);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('1{20},2{20},3{20},4{20},5{20}');
$task->execution->range('101-110{10}');
$task->name->range('任务1,任务2,任务3')->prefix('task_')->postfix('');
$task->type->range('design,devel,test');
$task->estimate->range('10,20,30,15,25');
$task->consumed->range('8,16,24,12,20');
$task->left->range('2,4,6,3,5');
$task->status->range('wait{40},doing{40},done{20}');
$task->isParent->range('0');
$task->deleted->range('0');
$task->closedReason->range('0{80},done{20}');
$task->gen(100);

$effort = zenData('effort');
$effort->id->range('1-200');
$effort->project->range('1{40},2{40},3{40},4{40},5{40}');
$effort->execution->range('101-110{20}');
$effort->objectType->range('task');
$effort->objectID->range('1-100{2}');
$effort->consumed->range('2,4,6,8,10');
$effort->deleted->range('0');
$effort->gen(200);

su('admin');

global $tester;
$tester->session->project = 1;
$blockTest = new blockZenTest();
r($blockTest->printWaterfallGeneralReportBlockTest()) && p('pv;ev;ac;sv;cv;progress') && e('2000;1680;240.00;-16.00;600.00;37.5'); // 测试项目1：有任务、有工时数据、部分任务已完成
$tester->session->project = 2;
r($blockTest->printWaterfallGeneralReportBlockTest()) && p('pv;ev;ac;sv;cv;progress') && e('0;0;240.00;0;-100.00;100'); // 测试项目2：有工时数据但无任务数据
$tester->session->project = 3;
r($blockTest->printWaterfallGeneralReportBlockTest()) && p('pv;ev;ac;sv;cv;progress') && e('0;0;240.00;0;-100.00;100'); // 测试项目3：有工时数据但无任务数据
$tester->session->project = 4;
r($blockTest->printWaterfallGeneralReportBlockTest()) && p('pv;ev;ac;sv;cv;progress') && e('0;0;240.00;0;-100.00;100'); // 测试项目4：有工时数据但无任务数据
$tester->session->project = 5;
r($blockTest->printWaterfallGeneralReportBlockTest()) && p('pv;ev;ac;sv;cv;progress') && e('0;0;240.00;0;-100.00;100'); // 测试项目5：有工时数据但无任务数据