#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::getReportsForBrowse();
timeout=0
cid=0

- 步骤1：测试产品类型默认参数 @4
- 步骤2：测试产品类型ID排序 @4
- 步骤3：测试产品类型不同分页大小 @4
- 步骤4：测试第2页返回结果 @4
- 步骤5：测试基本功能可访问性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$testreportTable = zenData('testreport');
$testreportTable->id->range('1-10');
$testreportTable->product->range('1-3');
$testreportTable->execution->range('1-5');
$testreportTable->title->range('测试报告1,测试报告2,测试报告3{7}');
$testreportTable->owner->range('admin,user1,user2{8}');
$testreportTable->objectType->range('testtask');
$testreportTable->objectID->range('1-5');
$testreportTable->deleted->range('0');
$testreportTable->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3{3}');
$productTable->deleted->range('0');
$productTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testreportTest = new testreportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($testreportTest->getReportsForBrowseTest(1, 'product'))) && p() && e('4'); // 步骤1：测试产品类型默认参数
r(count($testreportTest->getReportsForBrowseTest(1, 'product', 0, 'id_asc'))) && p() && e('4'); // 步骤2：测试产品类型ID排序
r(count($testreportTest->getReportsForBrowseTest(1, 'product', 0, 'id_desc', 0, 5))) && p() && e('4'); // 步骤3：测试产品类型不同分页大小
r(count($testreportTest->getReportsForBrowseTest(1, 'product', 0, 'id_desc', 0, 20, 2))) && p() && e('4'); // 步骤4：测试第2页返回结果
r(is_array($testreportTest->getReportsForBrowseTest(1, 'product')) ? 1 : 0) && p() && e('1'); // 步骤5：测试基本功能可访问性