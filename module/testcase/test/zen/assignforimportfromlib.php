#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignForImportFromLib();
timeout=0
cid=19070

- 步骤1：检查方法是否执行成功 @0
- 步骤2：不同参数执行 @0
- 步骤3：更多参数变化 @0
- 步骤4：带项目ID @0
- 步骤5：带用例数据 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->type->range('normal{3},branch{2}');
$productTable->status->range('normal');
$productTable->gen(5);

$testsuiteTable = zenData('testsuite');
$testsuiteTable->id->range('1-5');
$testsuiteTable->product->range('1-3');
$testsuiteTable->name->range('用例库1,用例库2,用例库3,用例库4,用例库5');
$testsuiteTable->type->range('library');
$testsuiteTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseZenTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseZenTest->assignForImportFromLibTest(1, '', 1, 'id_desc', 0, array(1 => '用例库1'), 0, array())) && p() && e('0'); // 步骤1：检查方法是否执行成功
r($testcaseZenTest->assignForImportFromLibTest(2, '', 2, 'id_asc', 1, array(2 => '用例库2'), 0, array())) && p() && e('0'); // 步骤2：不同参数执行
r($testcaseZenTest->assignForImportFromLibTest(3, '', 3, 'title_desc', 2, array(3 => '用例库3'), 0, array())) && p() && e('0'); // 步骤3：更多参数变化
r($testcaseZenTest->assignForImportFromLibTest(1, '0', 1, 'name_asc', 0, array(1 => '用例库1'), 1, array())) && p() && e('0'); // 步骤4：带项目ID
r($testcaseZenTest->assignForImportFromLibTest(1, 'all', 1, 'id_desc', 3, array(1 => '用例库1'), 0, array('case1'))) && p() && e('0'); // 步骤5：带用例数据