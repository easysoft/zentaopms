#!/usr/bin/env php
<?php

/**

title=测试 productZen::getProducts4DropMenu();
timeout=0
cid=17593

- 步骤1:在 product tab 下获取所有非影子产品 @10
- 步骤2:在 project tab 下获取项目关联的产品(无关联数据) @0
- 步骤3:在 product tab 下使用 shadow=all 参数 @10
- 步骤4:获取不存在项目的产品 @0
- 步骤5:验证返回结果为数组类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('product')->loadYaml('getproducts4dropmenu', false, 2)->gen(10);
zendata('projectproduct')->loadYaml('getproducts4dropmenu', false, 2)->gen(10);

su('admin');

$productTest = new productZenTest();

r(count($productTest->getProducts4DropMenuTest('0', '', 'product', 0))) && p() && e('10'); // 步骤1:在 product tab 下获取所有非影子产品
r(count($productTest->getProducts4DropMenuTest('0', '', 'project', 1))) && p() && e('0'); // 步骤2:在 project tab 下获取项目关联的产品(无关联数据)
r(count($productTest->getProducts4DropMenuTest('all', '', 'product', 0))) && p() && e('10'); // 步骤3:在 product tab 下使用 shadow=all 参数
r(count($productTest->getProducts4DropMenuTest('0', '', 'project', 999))) && p() && e('0'); // 步骤4:获取不存在项目的产品
r(is_array($productTest->getProducts4DropMenuTest('0', '', 'product', 0))) && p() && e('1'); // 步骤5:验证返回结果为数组类型