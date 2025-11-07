#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleMonthlyProgressBlock();
timeout=0
cid=0

- 测试步骤1:正常产品获取月度进度数据 >> 返回6个月的完成需求规模数据
- 测试步骤2:正常产品获取月度进度数据 >> 返回6个月的新增需求数数据
- 测试步骤3:正常产品获取月度进度数据 >> 返回6个月的完成需求数数据
- 测试步骤4:正常产品获取月度进度数据 >> 返回6个月的修复Bug数数据
- 测试步骤5:正常产品获取月度进度数据 >> 返回6个月的新增Bug数数据
- 测试步骤6:正常产品获取月度进度数据 >> 返回6个月的发布数数据
- 测试步骤7:验证不同产品ID的数据独立性 >> 返回对应产品的数据
- 测试步骤8:验证所有数据数组的长度一致性 >> 所有数组长度均为6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 准备产品测试数据
zenData('product')->loadYaml('product_printsinglemonthlyprogess block', false, 2)->gen(10);

// 准备度量项测试数据
zenData('metriclib')->loadYaml('metriclib_printsinglemonthlyprogess block', false, 2)->gen(100);

su('admin');

$blockTest = new blockZenTest();

// 步骤1:正常产品获取月度进度数据 - 验证返回6个月的完成需求规模数据
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('doneStoryEstimateCount') && e('6');
// 步骤2:正常产品获取月度进度数据 - 验证返回6个月的新增需求数数据
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('createStoryCountCount') && e('6');
// 步骤3:正常产品获取月度进度数据 - 验证返回6个月的完成需求数数据
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('doneStoryCountCount') && e('6');
// 步骤4:正常产品获取月度进度数据 - 验证返回6个月的修复Bug数数据
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('fixedBugCountCount') && e('6');
// 步骤5:正常产品获取月度进度数据 - 验证返回6个月的新增Bug数数据
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('createBugCountCount') && e('6');
// 步骤6:正常产品获取月度进度数据 - 验证返回6个月的发布数数据
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('releaseCountCount') && e('6');
// 步骤7:验证不同产品ID的数据独立性 - 返回对应产品的数据
r($blockTest->printSingleMonthlyProgressBlockTest(2)) && p('productID') && e('2');
// 步骤8:验证所有数据数组的长度一致性 - 所有数组长度均为6
r($blockTest->printSingleMonthlyProgressBlockTest(3)) && p('doneStoryEstimateCount,doneStoryCountCount,createStoryCountCount,fixedBugCountCount,createBugCountCount,releaseCountCount') && e('6,6,6,6,6,6');
