#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';
zdTable('user')->gen(5);
zdTable('product')->config('product')->gen(10);
zdTable('branch')->config('branch')->gen(10);
zdTable('productplan')->config('productplan')->gen(30);

/**

title=productpanModel->getPairs();
timeout=0
cid=1

*/

$productIdList = array(1, 2, 6);
$branchIdList  = array('', 'all', '2');
$paramList     = array('', 'unexpired', 'noclosed');
$skipParent    = array(false, true);

$planTester = new productPlan('admin');
r($planTester->getPairs($productIdList[0], $branchIdList[0], $paramList[0], $skipParent[0])) && p('3')  && e('计划3 [2022-01-01 ~ 2022-01-30]');          // 获取产品1下的所有计划
r($planTester->getPairs($productIdList[1], $branchIdList[0], $paramList[0], $skipParent[0])) && p('5')  && e('计划5 待定');                               // 获取产品2下的所有计划
r($planTester->getPairs($productIdList[2], $branchIdList[0], $paramList[0], $skipParent[0])) && p('16') && e('计划16 [2021-01-01 ~ 2021-01-30] / 主干');  // 获取产品6下的所有计划
r($planTester->getPairs($productIdList[0], $branchIdList[1], $paramList[0], $skipParent[0])) && p('3')  && e('计划3 [2022-01-01 ~ 2022-01-30]');          // 获取产品1下的所有计划
r($planTester->getPairs($productIdList[1], $branchIdList[1], $paramList[0], $skipParent[0])) && p('5')  && e('计划5 待定');                               // 获取产品2下的所有计划
r($planTester->getPairs($productIdList[2], $branchIdList[1], $paramList[0], $skipParent[0])) && p('16') && e('计划16 [2021-01-01 ~ 2021-01-30] / 主干');  // 获取产品6下的所有计划
r($planTester->getPairs($productIdList[2], $branchIdList[2], $paramList[0], $skipParent[0])) && p('17') && e('计划17 [2021-06-01 ~ 2021-06-30] / 分支2'); // 获取产品6下分支2的所有计划
r($planTester->getPairs($productIdList[0], $branchIdList[0], $paramList[1], $skipParent[0])) && p()     && e('0');                                        // 获取产品1下的所有未过期计划
r($planTester->getPairs($productIdList[1], $branchIdList[0], $paramList[1], $skipParent[0])) && p('5')  && e('计划5 待定');                               // 获取产品2下的所有未过期计划
r($planTester->getPairs($productIdList[2], $branchIdList[0], $paramList[1], $skipParent[0])) && p()     && e('0');                                        // 获取产品6下的所有未过期计划
r($planTester->getPairs($productIdList[0], $branchIdList[0], $paramList[2], $skipParent[0])) && p('3')  && e('计划3 [2022-01-01 ~ 2022-01-30]');          // 获取产品1下的所有未过期计划
r($planTester->getPairs($productIdList[1], $branchIdList[0], $paramList[2], $skipParent[0])) && p('5')  && e('计划5 待定');                               // 获取产品2下的所有未过期计划
r($planTester->getPairs($productIdList[2], $branchIdList[0], $paramList[2], $skipParent[0])) && p('18') && e('计划18 [2022-01-01 ~ 2022-01-30] / 主干');  // 获取产品6下的所有未过期计划
r($planTester->getPairs($productIdList[0], $branchIdList[0], $paramList[0], $skipParent[1])) && p('3')  && e('计划3 [2022-01-01 ~ 2022-01-30]');          // 获取产品1下的所有非父计划
r($planTester->getPairs($productIdList[1], $branchIdList[0], $paramList[0], $skipParent[1])) && p('5')  && e('计划5 待定');                               // 获取产品2下的所有非父计划
r($planTester->getPairs($productIdList[2], $branchIdList[0], $paramList[0], $skipParent[1])) && p('16') && e('计划16 [2021-01-01 ~ 2021-01-30] / 主干');  // 获取产品6下的所有非父计划
