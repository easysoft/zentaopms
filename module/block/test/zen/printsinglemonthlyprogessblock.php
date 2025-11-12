#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleMonthlyProgressBlock();
timeout=0
cid=0

- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是1 属性doneStoryEstimateCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是1 属性createStoryCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是1 属性fixedBugCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是2 属性productID @2
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是3
 - 属性doneStoryEstimateCount @6
 - 属性doneStoryCountCount @6
 - 属性createStoryCountCount @6
 - 属性fixedBugCountCount @6
 - 属性createBugCountCount @6
 - 属性releaseCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是1 属性doneStoryCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是1 属性createBugCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是1 属性releaseCountCount @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('product_printsinglemonthlyprogessblock', false, 2)->gen(10);
zenData('metriclib')->loadYaml('metriclib_printsinglemonthlyprogessblock', false, 2)->gen(100);

su('admin');

$blockTest = new blockZenTest();

r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('doneStoryEstimateCount') && e('6');
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('createStoryCountCount') && e('6');
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('fixedBugCountCount') && e('6');
r($blockTest->printSingleMonthlyProgressBlockTest(2)) && p('productID') && e('2');
r($blockTest->printSingleMonthlyProgressBlockTest(3)) && p('doneStoryEstimateCount,doneStoryCountCount,createStoryCountCount,fixedBugCountCount,createBugCountCount,releaseCountCount') && e('6,6,6,6,6,6');
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('doneStoryCountCount') && e('6');
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('createBugCountCount') && e('6');
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('releaseCountCount') && e('6');