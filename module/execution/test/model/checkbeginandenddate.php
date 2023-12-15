#!/usr/bin/env php
<?php

/**

title=测试executionModel->checkBeginAndEndDate();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project')->config('execution');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220215 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(30);

$projectIdList = array(11, 60, 100);
$parentIdList  = array(0, 106);
$beginList     = array('2022-01-01', '2022-01-13');
$endList       = array('2022-01-14', '2022-02-16');

$executionTester = new executionTest();
r($executionTester->checkBeginAndEndDateTest($projectIdList[0], $beginList[0], $endList[0], $parentIdList[0])) && p('begin') && e('迭代开始日期应大于等于项目的开始日期：2022-01-12。');    // 测试敏捷项目不正确的开始日期
r($executionTester->checkBeginAndEndDateTest($projectIdList[0], $beginList[1], $endList[0], $parentIdList[0])) && p()        && e('1');                                                     // 测试敏捷项目正确的开始跟结束日期
r($executionTester->checkBeginAndEndDateTest($projectIdList[0], $beginList[1], $endList[1], $parentIdList[0])) && p('end')   && e('迭代截止日期应小于等于项目的截止日期：2022-02-15。');    // 测试敏捷项目不正确的结束日期
r($executionTester->checkBeginAndEndDateTest($projectIdList[1], $beginList[0], $endList[0], $parentIdList[1])) && p('begin') && e('子阶段计划开始不能超过父阶段的计划开始时间 2022-01-12'); // 测试瀑布项目不正确的开始日期
r($executionTester->checkBeginAndEndDateTest($projectIdList[1], $beginList[1], $endList[0], $parentIdList[1])) && p()        && e('1');                                                     // 测试瀑布项目正确的开始跟结束日期
r($executionTester->checkBeginAndEndDateTest($projectIdList[1], $beginList[1], $endList[1], $parentIdList[1])) && p('end')   && e('子阶段计划完成不能超过父阶段的计划完成时间 2022-02-15'); // 测试瀑布项目不正确的结束日期
r($executionTester->checkBeginAndEndDateTest($projectIdList[2], $beginList[0], $endList[0], $parentIdList[0])) && p('begin') && e('迭代开始日期应大于等于项目的开始日期：2022-01-12。');    // 测试看板项目不正确的开始日期
r($executionTester->checkBeginAndEndDateTest($projectIdList[2], $beginList[1], $endList[0], $parentIdList[0])) && p()        && e('1');                                                     // 测试看板项目正确的开始跟结束日期
r($executionTester->checkBeginAndEndDateTest($projectIdList[2], $beginList[1], $endList[1], $parentIdList[0])) && p('end')   && e('迭代截止日期应小于等于项目的截止日期：2022-02-15。');    // 测试看板项目不正确的结束日期
