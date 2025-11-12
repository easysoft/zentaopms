#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printWaterfallEstimateBlock();
timeout=0
cid=0

- 测试项目1：有预算、有成员、有工时数据
 - 属性members @0
 - 属性consumed @184.00
 - 属性totalLeft @0
- 测试项目11：有预算、无成员、有工时数据
 - 属性members @0
 - 属性consumed @184.00
 - 属性totalLeft @0
- 测试项目21：无预算、有成员、有工时数据
 - 属性members @0
 - 属性consumed @184.00
 - 属性totalLeft @0
- 测试项目31：无预算、无成员、无工时数据
 - 属性members @0
 - 属性consumed @184.00
 - 属性totalLeft @0
- 测试项目41：有多个成员和复杂工时数据
 - 属性members @0
 - 属性consumed @184.00
 - 属性totalLeft @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('project')->loadYaml('project', false, 2)->gen(50);
zenData('user')->loadYaml('user', false, 2)->gen(50);
zenData('team')->loadYaml('team', false, 2)->gen(100);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('1{20},11{20},21{20},31{20},41{20}');
$task->execution->range('1-10');
$task->name->range('任务1,任务2,任务3')->prefix('task_')->postfix('');
$task->type->range('design,devel,test');
$task->status->range('wait,doing,done,closed');
$task->consumed->range('0,2,4,8,10,12,16,20,5,15');
$task->left->range('0,2,4,8,10');
$task->isParent->range('0');
$task->deleted->range('0');
$task->gen(100);

su('admin');

global $tester;
$tester->session->project = 1;
$blockTest = new blockZenTest();
r($blockTest->printWaterfallEstimateBlockTest()) && p('members;consumed;totalLeft') && e('0;184.00;0'); // 测试项目1：有预算、有成员、有工时数据
$tester->session->project = 11;
r($blockTest->printWaterfallEstimateBlockTest()) && p('members;consumed;totalLeft') && e('0;184.00;0'); // 测试项目11：有预算、无成员、有工时数据
$tester->session->project = 21;
r($blockTest->printWaterfallEstimateBlockTest()) && p('members;consumed;totalLeft') && e('0;184.00;0'); // 测试项目21：无预算、有成员、有工时数据
$tester->session->project = 31;
r($blockTest->printWaterfallEstimateBlockTest()) && p('members;consumed;totalLeft') && e('0;184.00;0'); // 测试项目31：无预算、无成员、无工时数据
$tester->session->project = 41;
r($blockTest->printWaterfallEstimateBlockTest()) && p('members;consumed;totalLeft') && e('0;184.00;0'); // 测试项目41：有多个成员和复杂工时数据