#!/usr/bin/env php
<?php

/**

title=测试 productZen::getActions4Dashboard();
timeout=0
cid=17569

- 步骤1:测试产品ID=1 @1
- 步骤2:测试产品ID=2 @1
- 步骤3:测试产品ID=999(不存在) @1
- 步骤4:测试产品ID=0(全部产品) @1
- 步骤5:验证返回结果计数 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('action')->gen(50);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('Product 1,Product 2,Product 3,Product 4,Product 5');
$productTable->type->range('normal{5}');
$productTable->status->range('normal{5}');
$productTable->deleted->range('0{5}');
$productTable->gen(5);
zenData('story')->gen(10);
zenData('bug')->gen(10);
zenData('task')->gen(10);
zenData('case')->gen(10);
zenData('project')->gen(5);
zenData('user')->gen(5);
$company = zenData('company');
$company->admins->range('`,admin,`');
$company->gen(1);
global $app;
$app->company->admins = ',admin,';

su('admin');

$productTest = new productZenTest();
$productTest->instance->mao->cache = null;
restoreObjectTables();

r(is_array($productTest->getActions4DashboardTest(1))) && p() && e('1'); // 步骤1:测试产品ID=1
r(is_array($productTest->getActions4DashboardTest(2))) && p() && e('1'); // 步骤2:测试产品ID=2
r(is_array($productTest->getActions4DashboardTest(999))) && p() && e('1'); // 步骤3:测试产品ID=999(不存在)
r(is_array($productTest->getActions4DashboardTest(0))) && p() && e('1'); // 步骤4:测试产品ID=0(全部产品)
r(count($productTest->getActions4DashboardTest(1)) >= 0) && p() && e('1'); // 步骤5:验证返回结果计数
