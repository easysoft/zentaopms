#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getBurnData();
timeout=0
cid=16300

- 执行executionTest模块的getBurnDataTest方法，参数是3 第3条的01-12:value属性 @75
- 执行executionTest模块的getBurnDataTest方法，参数是4 第4条的01-12:value属性 @75
- 执行executionTest模块的getBurnDataTest方法  @0
- 执行executionTest模块的getBurnDataTest方法，参数是999  @0
- 执行executionTest模块的getBurnDataTest方法，参数是3 第3条的01-12:name属性 @01-12

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-6');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1,测试执行1');
$execution->type->range('program,project,sprint,stage,kanban,sprint');
$execution->code->range('1-6')->prefix('code');
$execution->parent->range('0,1,2{4}');
$execution->status->range('wait{2},doing{2},suspended,closed');
$execution->openedBy->range('admin,user1{2}');
$execution->openedDate->range('20220105 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->begin->range('20220110 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220220 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(6);

$burn = zenData('burn');
$burn->execution->range('3{6},4{6},5{3}');
$burn->date->range('20220111 000000:1D')->type('timestamp')->format('YY/MM/DD');
$burn->estimate->range('100,90,80,70,60,50,45,40,35,30,25,20,15,10,5');
$burn->left->range('95,85,75,65,55,45,40,35,30,25,20,15,10,5,3');
$burn->consumed->range('5,15,25,35,45,55,60,65,70,75,80,85,90,95,97');
$burn->storyPoint->range('20,18,16,14,12,10,8,6,4,2,0');
$burn->task->range('0');
$burn->gen(15);

$executionTest = new executionModelTest();

r($executionTest->getBurnDataTest(3)) && p('3:01-12:value') && e('75');
r($executionTest->getBurnDataTest(4)) && p('4:01-12:value') && e('75');
r($executionTest->getBurnDataTest(0)) && p() && e('0');
r($executionTest->getBurnDataTest(999)) && p() && e('0');
r($executionTest->getBurnDataTest(3)) && p('3:01-12:name') && e('01-12');