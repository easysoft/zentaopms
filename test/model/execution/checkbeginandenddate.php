#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220215 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

/**

title=测试executionModel->checkBeginAndEndDate();
cid=1
pid=1

测试不正确的开始日期 >> 迭代开始日期应大于等于项目的开始日期：2022-01-12。
测试正确的开始跟结束日期 >> 1
测试不正确的结束日期 >> 迭代截止日期应小于等于项目的截止日期：2022-02-15。

*/

$projectID = 2;
$beginList = array('2022-01-01', '2022-01-13');
$endList   = array('2022-01-14', '2022-02-16');

$executionTester = new executionTest();
r($executionTester->checkBeginAndEndDateTest($projectID, $beginList[0], $endList[0])) && p('begin') && e('迭代开始日期应大于等于项目的开始日期：2022-01-12。'); // 测试不正确的开始日期
r($executionTester->checkBeginAndEndDateTest($projectID, $beginList[1], $endList[0])) && p()        && e('1');                                                  // 测试正确的开始跟结束日期
r($executionTester->checkBeginAndEndDateTest($projectID, $beginList[1], $endList[1])) && p('end')   && e('迭代截止日期应小于等于项目的截止日期：2022-02-15。'); // 测试不正确的结束日期
