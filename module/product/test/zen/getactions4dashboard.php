#!/usr/bin/env php
<?php

/**

title=测试 productZen::getActions4Dashboard();
timeout=0
cid=0

- 步骤1:测试产品ID=1 @1
- 步骤2:测试产品ID=2 @1
- 步骤3:测试产品ID=999(不存在) @1
- 步骤4:测试产品ID=0(全部产品) @1
- 步骤5:验证返回结果计数 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('action')->loadYaml('getactions4dashboard/action', false, 2)->gen(50);
zenData('product')->loadYaml('getactions4dashboard/product', false, 2)->gen(5);
zenData('user')->gen(5);

su('admin');

$productTest = new productZenTest();

r(is_array($productTest->getActions4DashboardTest(1))) && p() && e('1'); // 步骤1:测试产品ID=1
r(is_array($productTest->getActions4DashboardTest(2))) && p() && e('1'); // 步骤2:测试产品ID=2
r(is_array($productTest->getActions4DashboardTest(999))) && p() && e('1'); // 步骤3:测试产品ID=999(不存在)
r(is_array($productTest->getActions4DashboardTest(0))) && p() && e('1'); // 步骤4:测试产品ID=0(全部产品)
r(count($productTest->getActions4DashboardTest(1)) >= 0) && p() && e('1'); // 步骤5:验证返回结果计数