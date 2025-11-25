#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getUsageReportProducts();
timeout=0
cid=18259

- 执行screenTest模块的getUsageReportProductsTest方法，参数是'2023', '01'  @2
- 执行screenTest模块的getUsageReportProductsTest方法，参数是'2024', '12'  @8
- 执行screenTest模块的getUsageReportProductsTest方法，参数是'2030', '12'  @8
- 执行screenTest模块的getUsageReportProductsTest方法，参数是'2020', '01'  @1
- 执行screenTest模块的getUsageReportProductsTest方法，参数是'2024', '06'  @7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品A,产品B,产品C,测试产品D,示例产品E{5}');
$table->createdDate->range('`2020-01-01`,`2023-01-15`,`2023-12-31`,`2024-06-15`,`2024-06-16`,`2024-06-17`,`2024-06-18`,`2024-12-31`,`2024-12-30`,`2024-12-29`');
$table->deleted->range('0{8},1{2}');
$table->shadow->range('0{9},1{1}');
$table->gen(10);

su('admin');

$screenTest = new screenTest();

r($screenTest->getUsageReportProductsTest('2023', '01')) && p() && e('2');
r($screenTest->getUsageReportProductsTest('2024', '12')) && p() && e('8');
r($screenTest->getUsageReportProductsTest('2030', '12')) && p() && e('8');
r($screenTest->getUsageReportProductsTest('2020', '01')) && p() && e('1');
r($screenTest->getUsageReportProductsTest('2024', '06')) && p() && e('7');