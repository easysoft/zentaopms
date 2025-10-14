#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::checkProducts();
timeout=0
cid=0

- 步骤1：qa模式下有产品的情况 @no_redirect
- 步骤2：qa模式下无产品但非AJAX请求 @no_redirect
- 步骤3：qa模式下无产品且为AJAX zin请求 @redirect_to_error_page
- 步骤4：project模式下有产品的情况 @no_redirect
- 步骤5：execution模式下无产品且为AJAX fetch请求 @redirect_to_error_page

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->status->range('normal{5}');
$product->deleted->range('0{5}');
$product->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->checkProductsTest(array(1 => '产品1', 2 => '产品2'), 'qa', 0, 0, false, false)) && p() && e('no_redirect'); // 步骤1：qa模式下有产品的情况
r($testcaseTest->checkProductsTest(array(), 'qa', 0, 0, false, false)) && p() && e('no_redirect'); // 步骤2：qa模式下无产品但非AJAX请求
r($testcaseTest->checkProductsTest(array(), 'qa', 0, 0, true, false)) && p() && e('redirect_to_error_page'); // 步骤3：qa模式下无产品且为AJAX zin请求
r($testcaseTest->checkProductsTest(array(1 => '产品1'), 'project', 1, 0, false, false)) && p() && e('no_redirect'); // 步骤4：project模式下有产品的情况
r($testcaseTest->checkProductsTest(array(), 'execution', 0, 1, false, true)) && p() && e('redirect_to_error_page'); // 步骤5：execution模式下无产品且为AJAX fetch请求