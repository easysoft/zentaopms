#!/usr/bin/env php
<?php

/**

title=测试 productZen::getExportData();
timeout=0
cid=17581

- 步骤1:测试获取全部产品导出数据 @10
- 步骤2:测试获取项目集1的产品导出数据 @1
- 步骤3:测试获取项目集2的产品导出数据 @1
- 步骤4:测试获取全部产品导出数据不受browseType影响 @10
- 步骤5:测试返回数据包含formatDataForList处理后的字段第0条的type属性 @product

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('product')->loadYaml('getexportdata', false, 2)->gen(10);
zendata('user')->loadYaml('getexportdata', false, 2)->gen(10);

su('admin');

$productTest = new productZenTest();

r(count($productTest->getExportDataTest(0, 'all', 'order_asc', 0, null))) && p() && e('10'); // 步骤1:测试获取全部产品导出数据
r(count($productTest->getExportDataTest(1, 'all', 'order_asc', 0, null))) && p() && e('1'); // 步骤2:测试获取项目集1的产品导出数据
r(count($productTest->getExportDataTest(2, 'all', 'order_asc', 0, null))) && p() && e('1'); // 步骤3:测试获取项目集2的产品导出数据
r(count($productTest->getExportDataTest(0, 'noclosed', 'order_asc', 0, null))) && p() && e('10'); // 步骤4:测试获取全部产品导出数据不受browseType影响
r($productTest->getExportDataTest(0, 'all', 'order_asc', 0, null)) && p('0:type') && e('product'); // 步骤5:测试返回数据包含formatDataForList处理后的字段