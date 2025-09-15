#!/usr/bin/env php
<?php

/**

title=测试 productZen::getUnauthProgramsOfProducts();
timeout=0
cid=0

- 执行productTest模块的getUnauthProgramsOfProductsTest方法，参数是$products, $authPrograms 
 - 属性2 @项目集2
 - 属性3 @项目集3
- 执行productTest模块的getUnauthProgramsOfProductsTest方法，参数是$emptyProducts, $authPrograms  @0
- 执行productTest模块的getUnauthProgramsOfProductsTest方法，参数是$products, $authPrograms  @0
- 执行productTest模块的getUnauthProgramsOfProductsTest方法，参数是$products, $authPrograms  @0
- 执行productTest模块的getUnauthProgramsOfProductsTest方法，参数是$products, $authPrograms 
 - 属性3 @项目集3
 - 属性4 @项目集4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->loadYaml('product_getunauth', false, 2)->gen(10);

$projectTable = zenData('project');
$projectTable->loadYaml('project_getunauth', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 测试步骤1：正常情况下获取未授权项目集
$products = array(
    (object)array('id' => 1, 'program' => 1, 'name' => '产品1'),
    (object)array('id' => 2, 'program' => 2, 'name' => '产品2'),
    (object)array('id' => 3, 'program' => 3, 'name' => '产品3')
);
$authPrograms = array(1 => '项目集1');
r($productTest->getUnauthProgramsOfProductsTest($products, $authPrograms)) && p('2,3') && e('项目集2,项目集3');

// 测试步骤2：产品列表为空时的处理
$emptyProducts = array();
$authPrograms = array(1 => '项目集1');
r($productTest->getUnauthProgramsOfProductsTest($emptyProducts, $authPrograms)) && p() && e('0');

// 测试步骤3：已授权项目集包含所有产品项目集
$products = array(
    (object)array('id' => 1, 'program' => 1, 'name' => '产品1'),
    (object)array('id' => 2, 'program' => 2, 'name' => '产品2')
);
$authPrograms = array(1 => '项目集1', 2 => '项目集2');
r($productTest->getUnauthProgramsOfProductsTest($products, $authPrograms)) && p() && e('0');

// 测试步骤4：产品没有关联项目集的情况
$products = array(
    (object)array('id' => 1, 'program' => 0, 'name' => '产品1'),
    (object)array('id' => 2, 'program' => '', 'name' => '产品2')
);
$authPrograms = array(1 => '项目集1');
r($productTest->getUnauthProgramsOfProductsTest($products, $authPrograms)) && p() && e('0');

// 测试步骤5：混合场景：部分产品有未授权项目集
$products = array(
    (object)array('id' => 1, 'program' => 1, 'name' => '产品1'),  // 已授权
    (object)array('id' => 2, 'program' => 0, 'name' => '产品2'),  // 无项目集
    (object)array('id' => 3, 'program' => 3, 'name' => '产品3'),  // 未授权
    (object)array('id' => 4, 'program' => 4, 'name' => '产品4')   // 未授权
);
$authPrograms = array(1 => '项目集1', 2 => '项目集2');
r($productTest->getUnauthProgramsOfProductsTest($products, $authPrograms)) && p('3,4') && e('项目集3,项目集4');