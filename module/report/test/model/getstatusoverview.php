#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 reportModel::getStatusOverview();
timeout=0
cid=18167

- 测试story对象类型的正常状态统计 @总需求 &nbsp; 21<br />未完成 &nbsp; 16
- 测试task对象类型的正常状态统计 @总任务 &nbsp; 21<br />未完成 &nbsp; 6
- 测试bug对象类型的正常状态统计 @总Bug &nbsp; 21<br />未解决 &nbsp; 1
- 测试空状态统计数组的边界情况 @总需求 &nbsp; 0<br />未完成 &nbsp; 0
- 测试单一状态的状态统计数组 @总任务 &nbsp; 10<br />未完成 &nbsp; 10
- 测试所有已完成状态的统计数组 @总需求 &nbsp; 20<br />未完成 &nbsp; 0
- 测试无效对象类型的错误处理 @ &nbsp; 21<br />未完成 &nbsp; 0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

zenData('user')->gen(5);

su('admin');

$report = new reportTest();

// 测试数据准备
$normalStatusStat = array('active' => 1, 'wait' => 2, 'doing' => 3, 'done' => 4, 'closed' => 5, 'cancel' => 6);
$emptyStatusStat = array();
$singleStatusStat = array('active' => 10);
$allDoneStatusStat = array('closed' => 20);
$largeNumberStat = array('active' => 100, 'wait' => 200, 'doing' => 150, 'done' => 300, 'closed' => 250);

r($report->getStatusOverviewTest('story', $normalStatusStat)) && p() && e('总需求 &nbsp; 21<br />未完成 &nbsp; 16'); // 测试story对象类型的正常状态统计
r($report->getStatusOverviewTest('task', $normalStatusStat)) && p() && e('总任务 &nbsp; 21<br />未完成 &nbsp; 6');   // 测试task对象类型的正常状态统计
r($report->getStatusOverviewTest('bug', $normalStatusStat)) && p() && e('总Bug &nbsp; 21<br />未解决 &nbsp; 1');     // 测试bug对象类型的正常状态统计
r($report->getStatusOverviewTest('story', $emptyStatusStat)) && p() && e('总需求 &nbsp; 0<br />未完成 &nbsp; 0');    // 测试空状态统计数组的边界情况
r($report->getStatusOverviewTest('task', $singleStatusStat)) && p() && e('总任务 &nbsp; 10<br />未完成 &nbsp; 10'); // 测试单一状态的状态统计数组
r($report->getStatusOverviewTest('story', $allDoneStatusStat)) && p() && e('总需求 &nbsp; 20<br />未完成 &nbsp; 0'); // 测试所有已完成状态的统计数组
r($report->getStatusOverviewTest('invalidtype', $normalStatusStat)) && p() && e(' &nbsp; 21<br />未完成 &nbsp; 0');     // 测试无效对象类型的错误处理