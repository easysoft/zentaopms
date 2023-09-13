#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
su('admin');

$today = helper::today();
$productplan = zdTable('productplan')->config('productplan');
$productplan->end->range("`2022-01-30`{5},`{$today}`{5}");
$productplan->gen(30);

/**

title=productplanModel->getProductPlans();
timeout=0
cid=1

*/

$productIdList = array(array(), array(1, 2, 3));
$endList       = array('', $today, '2022-01-30');

global $tester;
$tester->loadModel('productplan');
r(current($tester->productplan->getProductPlans($productIdList[0], $endList[0]))) && p('0:product,title') && e('1,计划1'); // 获取系统内所有产品下的计划
r(current($tester->productplan->getProductPlans($productIdList[0], $endList[1]))) && p('0:product,title') && e('2,计划6'); // 获取系统内所有产品下的未过期的计划
r(current($tester->productplan->getProductPlans($productIdList[0], $endList[2]))) && p('0:product,title') && e('2,计划6'); // 获取系统内所有产品下的结束日期在2022-01-30之后的计划
r(current($tester->productplan->getProductPlans($productIdList[1], $endList[0]))) && p('0:product,title') && e('1,计划1'); // 获取系统内产品1、产品2、产品3下的计划
r(current($tester->productplan->getProductPlans($productIdList[1], $endList[1]))) && p('0:product,title') && e('2,计划6'); // 获取系统内产品1、产品2、产品3下的未过期计划
r(current($tester->productplan->getProductPlans($productIdList[1], $endList[2]))) && p('0:product,title') && e('2,计划6'); // 获取系统内产品1、产品2、产品3下的结束日期在2022-01-30之后的计划

r(count($tester->productplan->getProductPlans($productIdList[0], $endList[0]))) && p() && e('10'); // 获取系统内所有产品下的计划数量
r(count($tester->productplan->getProductPlans($productIdList[0], $endList[1]))) && p() && e('7');  // 获取系统内所有产品下的未过期的计划的数量
r(count($tester->productplan->getProductPlans($productIdList[0], $endList[2]))) && p() && e('7');  // 获取系统内所有产品下的结束日期在2022-01-30之后的计划的数量
r(count($tester->productplan->getProductPlans($productIdList[1], $endList[0]))) && p() && e('3');  // 获取系统内产品1、产品2、产品3下的计划的数量
r(count($tester->productplan->getProductPlans($productIdList[1], $endList[1]))) && p() && e('2');  // 获取系统内产品1、产品2、产品3下的未过期计划的数量
r(count($tester->productplan->getProductPlans($productIdList[1], $endList[2]))) && p() && e('2');  // 获取系统内产品1、产品2、产品3下的结束日期在2022-01-30之后的计划的数量
