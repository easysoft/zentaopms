#!/usr/bin/env php
<?php
/**

title=productpanModel->getForProducts();
timeout=0
cid=17633

- 测试传入一个数组，取出产品名称count属性1 @计划1
- 测试传入一个不存在的product数组,应为空 @0
- 测试传入一个空的product数组,应为空 @0
- 测试传入一个带有空的product数组,应为空属性1 @计划1
- 测试传入一个带有不存在ID的product数组,应为空属性1 @计划1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('productplan')->loadYaml('productplan')->gen(5);
zenData('user')->gen(5);
su('admin');

$productIdList = array();
$productIdList[0] = array(1, 2);
$productIdList[1] = array(1000,1001);
$productIdList[2] = array();
$productIdList[3] = array(0, 1);
$productIdList[4] = array(1, 1000);

global $tester,$app;
$app->moduleName = 'productplan';
$app->rawModule = 'productplan';
$tester->loadModel('productplan');

r($tester->productplan->getForProducts($productIdList[0]))        && p('1') && e('计划1'); // 测试传入一个数组，取出产品名称count
r(count($tester->productplan->getForProducts($productIdList[1]))) && p()    && e('0');     // 测试传入一个不存在的product数组,应为空
r(count($tester->productplan->getForProducts($productIdList[2]))) && p()    && e('0');     // 测试传入一个空的product数组,应为空
r($tester->productplan->getForProducts($productIdList[3]))        && p('1') && e('计划1'); // 测试传入一个带有空的product数组,应为空
r($tester->productplan->getForProducts($productIdList[4]))        && p('1') && e('计划1'); // 测试传入一个带有不存在ID的product数组,应为空
