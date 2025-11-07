#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleMonthlyProgressBlock();
timeout=0
cid=0

- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是1 属性doneStoryEstimateCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是2 属性doneStoryCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是3 属性createStoryCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是4 属性fixedBugCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是5 属性createBugCountCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是1 属性releaseCountCount @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 准备产品测试数据
zenData('product')->loadYaml('product_printsinglemonthlyprogessblock', false, 2)->gen(5);

// 准备度量项测试数据
zenData('metriclib')->loadYaml('metriclib_printsinglemonthlyprogessblock', false, 2)->gen(100);

su('admin');

$blockTest = new blockZenTest();

// 步骤1:正常情况测试 - 验证产品ID为1时完成需求工时数组有6个月数据
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('doneStoryEstimateCount') && e('6');
// 步骤2:验证产品ID为2时完成需求数数组有6个月数据
r($blockTest->printSingleMonthlyProgressBlockTest(2)) && p('doneStoryCountCount') && e('6');
// 步骤3:验证产品ID为3时新增需求数数组有6个月数据
r($blockTest->printSingleMonthlyProgressBlockTest(3)) && p('createStoryCountCount') && e('6');
// 步骤4:验证产品ID为4时修复Bug数数组有6个月数据
r($blockTest->printSingleMonthlyProgressBlockTest(4)) && p('fixedBugCountCount') && e('6');
// 步骤5:验证产品ID为5时新增Bug数数组有6个月数据
r($blockTest->printSingleMonthlyProgressBlockTest(5)) && p('createBugCountCount') && e('6');
// 步骤6:验证产品ID为1时发布数数组有6个月数据
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('releaseCountCount') && e('6');