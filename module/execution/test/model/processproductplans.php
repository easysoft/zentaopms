#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
/**

title=测试executionModel->processProductPlans();
timeout=0
cid=16357

- 处理产品的计划信息第5条的15属性 @计划15 待定
- 处理产品的带主干计划信息第4条的10属性 @计划10 待定
- 处理产品的非父计划信息第2条的5属性 @计划5 待定
- 处理产品的计划数量 @5
- 处理产品的带主干计划数量 @5
- 处理产品的非父计划数量 @5

*/

zenData('user')->gen(5);
su('admin');

zenData('product')->loadYaml('product')->gen(10);
zenData('productplan')->loadYaml('productplan')->gen(30);

$productIdList = array(1, 2, 3, 4, 5);
$paramList     = array('', 'withmainplan', 'skipparent');

$executionTester = new executionTest();
r($executionTester->processProductPlansTest($productIdList, $paramList[0]))        && p('5:15') && e('计划15 待定'); // 处理产品的计划信息
r($executionTester->processProductPlansTest($productIdList, $paramList[1]))        && p('4:10') && e('计划10 待定'); // 处理产品的带主干计划信息
r($executionTester->processProductPlansTest($productIdList, $paramList[2]))        && p('2:5')  && e('计划5 待定');  // 处理产品的非父计划信息
r(count($executionTester->processProductPlansTest($productIdList, $paramList[0]))) && p()       && e('5');           // 处理产品的计划数量
r(count($executionTester->processProductPlansTest($productIdList, $paramList[1]))) && p()       && e('5');           // 处理产品的带主干计划数量
r(count($executionTester->processProductPlansTest($productIdList, $paramList[2]))) && p()       && e('5');           // 处理产品的非父计划数量
