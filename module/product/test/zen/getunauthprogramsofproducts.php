#!/usr/bin/env php
<?php

/**

title=测试 productZen::getUnauthProgramsOfProducts();
timeout=0
cid=0

- 执行productTest模块的getUnauthProgramsOfProductsTest方法，参数是$products1, $authPrograms1  @1
- 执行productTest模块的getUnauthProgramsOfProductsTest方法，参数是$products2, $authPrograms2  @0
- 执行productTest模块的getUnauthProgramsOfProductsTest方法，参数是$products3, $authPrograms3  @0
- 执行productTest模块的getUnauthProgramsOfProductsTest方法，参数是$products4, $authPrograms4  @0
- 执行productTest模块的getUnauthProgramsOfProductsTest方法，参数是$products5, $authPrograms5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('project')->loadYaml('getunauthprogramsofproducts', false, 2)->gen(30);

su('admin');

$productTest = new productZenTest();

// 准备测试数据
// 测试步骤1: 产品关联未授权项目集
$products1 = array();
$product1 = new stdclass();
$product1->id = 1;
$product1->name = 'Product 1';
$product1->program = 10;
$products1[] = $product1;

$authPrograms1 = array(1 => 'Authorized Program 1', 2 => 'Authorized Program 2');

// 测试步骤2: 产品无关联项目集(program=0)
$products2 = array();
$product2 = new stdclass();
$product2->id = 2;
$product2->name = 'Product 2';
$product2->program = 0;
$products2[] = $product2;

$authPrograms2 = array(1 => 'Authorized Program 1');

// 测试步骤3: 产品关联的项目集都已授权
$products3 = array();
$product3 = new stdclass();
$product3->id = 3;
$product3->name = 'Product 3';
$product3->program = 5;
$products3[] = $product3;

$authPrograms3 = array(5 => 'Authorized Program 5', 6 => 'Authorized Program 6');

// 测试步骤4: 空产品列表
$products4 = array();
$authPrograms4 = array(1 => 'Authorized Program 1');

// 测试步骤5: 单个产品关联未授权项目集(使用生成的项目集ID:10)
$products5 = array();
$product5 = new stdclass();
$product5->id = 15;
$product5->name = 'Product 15';
$product5->program = 10;
$products5[] = $product5;

$authPrograms5 = array(5 => 'Authorized Program 5', 11 => 'Authorized Program 11');

r(count($productTest->getUnauthProgramsOfProductsTest($products1, $authPrograms1))) && p() && e('1');
r(count($productTest->getUnauthProgramsOfProductsTest($products2, $authPrograms2))) && p() && e('0');
r(count($productTest->getUnauthProgramsOfProductsTest($products3, $authPrograms3))) && p() && e('0');
r(count($productTest->getUnauthProgramsOfProductsTest($products4, $authPrograms4))) && p() && e('0');
r(count($productTest->getUnauthProgramsOfProductsTest($products5, $authPrograms5))) && p() && e('1');