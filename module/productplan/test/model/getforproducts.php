#!/usr/bin/env php
<?php
/**

title=productpanModel->getForProducts();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('productplan')->config('productplan')->gen(5);
zdTable('user')->gen(5);
su('admin');

$productIdList = array();
$productIdList[0] = array(1, 2);
$productIdList[1] = array(1000,1001);

global $tester,$app;
$app->moduleName = 'productplan';
$app->rawModule = 'productplan';
$tester->loadModel('productplan');

r($tester->productplan->getForProducts($productIdList[0]))        && p('1') && e('计划1'); // 测试传入一个数组，取出产品名称count
r(count($tester->productplan->getForProducts($productIdList[1]))) && p()    && e('0');     // 测试传入一个不存在的product数组,应为空
