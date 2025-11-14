#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$executionTester = new executionTest();

// 测试场景1：正常execution记录order排序
$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,迭代1,阶段1,看板1,项目2');
$execution->type->range('project,sprint,stage,kanban,project');
$execution->parent->range('0,1,1,1,0');
$execution->status->range('wait,doing,suspended,closed,wait');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->order->range('1,2,3,4,5');
$execution->gen(5);

/**

title=测试 executionModel::fixOrder();
timeout=0
cid=16296

- 测试正常order排序第1条第1条的order属性 @5
- 测试正常order排序第3条第3条的order属性 @15
- 测试重复order值处理第2条第2条的order属性 @10
- 测试乱序order值修正第4条第4条的order属性 @20
- 测试空数据库处理结果 @rray()

*/

r($executionTester->fixOrderTest()) && p('1:order') && e('5'); // 测试正常order排序第1条
r($executionTester->fixOrderTest()) && p('3:order') && e('15'); // 测试正常order排序第3条

// 测试场景2：重复order值的处理
zenData('project')->gen(0);
$execution = zenData('project');
$execution->id->range('1-4');
$execution->name->range('项目A,项目B,项目C,项目D');
$execution->type->range('project{4}');
$execution->parent->range('0{4}');
$execution->status->range('wait{4}');
$execution->openedBy->range('admin{4}');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->order->range('10,10,10,10');
$execution->gen(4);

r($executionTester->fixOrderTest()) && p('2:order') && e('10'); // 测试重复order值处理第2条

// 测试场景3：乱序order值的修正
zenData('project')->gen(0);
$execution = zenData('project');
$execution->id->range('1-6');
$execution->name->range('项目X,项目Y,项目Z,项目W,项目V,项目U');
$execution->type->range('project{6}');
$execution->parent->range('0{6}');
$execution->status->range('wait{6}');
$execution->openedBy->range('admin{6}');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->order->range('100,1,50,2,25,75');
$execution->gen(6);

r($executionTester->fixOrderTest()) && p('4:order') && e('20'); // 测试乱序order值修正第4条

// 测试场景4：空数据库的处理
zenData('project')->gen(0);

r($executionTester->fixOrderTest()) && p() && e(array()); // 测试空数据库处理结果