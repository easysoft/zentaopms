#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleMonthlyProgressBlock();
timeout=0
cid=15298

- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是1 属性doneStoryEstimateCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是2 属性doneStoryCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是3 属性createStoryCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是4 属性fixedBugCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是5 属性createBugCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是6 属性releaseCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是7
 - 属性doneStoryEstimateCount @6
 - 属性doneStoryCountCount @6
 - 属性createStoryCountCount @6
 - 属性fixedBugCountCount @6
 - 属性createBugCountCount @6
 - 属性releaseCountCount @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('product')->loadYaml('printsinglemonthlyprogressblock', false, 2)->gen(10);
zendata('metriclib')->loadYaml('printsinglemonthlyprogressblock', false, 2)->gen(6);

su('admin');

$blockTest = new blockZenTest();

r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('doneStoryEstimateCount') && e('6');
r($blockTest->printSingleMonthlyProgressBlockTest(2)) && p('doneStoryCountCount') && e('6');
r($blockTest->printSingleMonthlyProgressBlockTest(3)) && p('createStoryCountCount') && e('6');
r($blockTest->printSingleMonthlyProgressBlockTest(4)) && p('fixedBugCountCount') && e('6');
r($blockTest->printSingleMonthlyProgressBlockTest(5)) && p('createBugCountCount') && e('6');
r($blockTest->printSingleMonthlyProgressBlockTest(6)) && p('releaseCountCount') && e('6');
r($blockTest->printSingleMonthlyProgressBlockTest(7)) && p('doneStoryEstimateCount,doneStoryCountCount,createStoryCountCount,fixedBugCountCount,createBugCountCount,releaseCountCount') && e('6,6,6,6,6,6');