#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(200);
su('admin');

zenData('project')->loadYaml('execution')->gen(30);
zenData('product')->loadYaml('product')->gen(30);
zenData('projectproduct')->loadYaml('projectproduct')->gen(60);

/**

title=测试 executionModel->getProductList();
timeout=0
cid=16391

- 测试空数据 @0
- 测试获取敏捷项目下执行的产品个数 @5
- 测试获取敏捷项目下执行的产品
 - 属性product @5,
 - 属性productName @产品5,
- 测试获取不存在项目下执行的产品 @0

*/

global $tester;
$executionModel = $tester->loadModel('execution');

r($executionModel->getProductList(0))           && p()                           && e('0');         // 测试空数据
r(count($executionModel->getProductList(11)))   && p()                           && e('5');         // 测试获取敏捷项目下执行的产品个数
r(current($executionModel->getProductList(11))) && p('product;productName', ';') && e('5,;产品5,'); // 测试获取敏捷项目下执行的产品
r($executionModel->getProductList(1))           && p()                           && e('0');         // 测试获取不存在项目下执行的产品