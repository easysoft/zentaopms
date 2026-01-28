#!/usr/bin/env php
<?php

/**

title=测试通过项目id查询关联的产品id productModel->getProductIDByProject();
timeout=0
cid=17500

- 查询项目ID为1的关联产品 @0
- 查询项目ID为11的关联产品 @1
- 查询项目ID为60的关联产品 @2
- 查询项目ID为61的关联产品 @3
- 查询项目ID为100的关联产品 @4
- 查询项目ID为101的关联产品 @5
- 查询项目ID为102的关联产品 @6
- 不存在的项目ID @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->gen(20);
zenData('product')->gen(20);
zenData('projectproduct')->loadYaml('projectproduct')->gen(50);

$product = new productTest('admin');
r($product->getProductIDByProjectTest(1))   && p() && e('0'); // 查询项目ID为1的关联产品
r($product->getProductIDByProjectTest(11))  && p() && e('1'); // 查询项目ID为11的关联产品
r($product->getProductIDByProjectTest(60))  && p() && e('2'); // 查询项目ID为60的关联产品
r($product->getProductIDByProjectTest(61))  && p() && e('3'); // 查询项目ID为61的关联产品
r($product->getProductIDByProjectTest(100)) && p() && e('4'); // 查询项目ID为100的关联产品
r($product->getProductIDByProjectTest(101)) && p() && e('5'); // 查询项目ID为101的关联产品
r($product->getProductIDByProjectTest(102)) && p() && e('6'); // 查询项目ID为102的关联产品
r($product->getProductIDByProjectTest(150)) && p() && e('0'); // 不存在的项目ID
