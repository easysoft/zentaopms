#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkProductPriv();
timeout=0
cid=0

- 步骤1：正常情况，有权限 @2
- 步骤2：部分权限 @2
- 步骤3：无权限 @0
- 步骤4：过滤shadow产品 @1
- 步骤5：空结果集 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->shadow->range('0{4},1');
$product->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$searchTest = new searchTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($searchTest->checkProductPrivTest(array(1 => (object)array('id' => 1, 'title' => 'test1'), 2 => (object)array('id' => 2, 'title' => 'test2')), array(1 => 1, 2 => 2), '1,2,3'))) && p() && e('2'); // 步骤1：正常情况，有权限
r(count($searchTest->checkProductPrivTest(array(1 => (object)array('id' => 1, 'title' => 'test1'), 2 => (object)array('id' => 2, 'title' => 'test2'), 3 => (object)array('id' => 3, 'title' => 'test3')), array(1 => 1, 2 => 2, 4 => 3), '1,2'))) && p() && e('2'); // 步骤2：部分权限
r(count($searchTest->checkProductPrivTest(array(1 => (object)array('id' => 1, 'title' => 'test1'), 2 => (object)array('id' => 2, 'title' => 'test2')), array(3 => 1, 4 => 2), '1,2'))) && p() && e('0'); // 步骤3：无权限
r(count($searchTest->checkProductPrivTest(array(1 => (object)array('id' => 1, 'title' => 'test1'), 2 => (object)array('id' => 2, 'title' => 'test2')), array(1 => 1, 5 => 2), '1,5'))) && p() && e('1'); // 步骤4：过滤shadow产品
r(count($searchTest->checkProductPrivTest(array(), array(), '1,2'))) && p() && e('0'); // 步骤5：空结果集