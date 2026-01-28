#!/usr/bin/env php
<?php
/**

title=productpanModel->getPairs();
timeout=0
cid=17637

- 获取产品1下的所有计划属性3 @计划3 [2022-01-01 ~ 2022-01-30]
- 获取产品2下的所有计划属性5 @计划5 待定
- 获取产品6下的所有计划属性16 @计划16 [2021-01-01 ~ 2021-06-30] / 主干
- 获取产品1下的所有计划属性3 @计划3 [2022-01-01 ~ 2022-01-30]
- 获取产品2下的所有计划属性5 @计划5 待定
- 获取产品6下的所有计划属性16 @计划16 [2021-01-01 ~ 2021-06-30] / 主干
- 获取产品6下分支2的所有计划属性17 @计划17 [2021-06-01 ~ 2021-06-15] / 分支2
- 获取产品1下的所有未过期计划 @0
- 获取产品2下的所有未过期计划属性5 @计划5 待定
- 获取产品6下的所有未过期计划 @0
- 获取产品1下的所有未过期计划属性3 @计划3 [2022-01-01 ~ 2022-01-30]
- 获取产品2下的所有未过期计划属性5 @计划5 待定
- 获取产品6下的所有未过期计划属性18 @计划18 [2022-01-01 ~ 2022-01-30] / 主干
- 获取产品1下的所有非父计划属性3 @计划3 [2022-01-01 ~ 2022-01-30]
- 获取产品2下的所有非父计划属性5 @计划5 待定
- 获取产品6下的所有非父计划属性16 @计划16 [2021-01-01 ~ 2021-06-30] / 主干

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
zenData('product')->loadYaml('product')->gen(10);
zenData('branch')->loadYaml('branch')->gen(10);
zenData('productplan')->loadYaml('productplan')->gen(30);

$productIdList = array(1, 2, 6);
$branchIdList  = array('', 'all', '2');
$paramList     = array('', 'unexpired', 'noclosed');
$skipParent    = array(false, true);

$planTester = new productPlan('admin');
r($planTester->getPairs($productIdList[0], $branchIdList[0], $paramList[0], $skipParent[0])) && p('3')  && e('计划3 [2022-01-01 ~ 2022-01-30]');          // 获取产品1下的所有计划
r($planTester->getPairs($productIdList[1], $branchIdList[0], $paramList[0], $skipParent[0])) && p('5')  && e('计划5 待定');                               // 获取产品2下的所有计划
r($planTester->getPairs($productIdList[2], $branchIdList[0], $paramList[0], $skipParent[0])) && p('16') && e('计划16 [2021-01-01 ~ 2021-06-30] / 主干');  // 获取产品6下的所有计划
r($planTester->getPairs($productIdList[0], $branchIdList[1], $paramList[0], $skipParent[0])) && p('3')  && e('计划3 [2022-01-01 ~ 2022-01-30]');          // 获取产品1下的所有计划
r($planTester->getPairs($productIdList[1], $branchIdList[1], $paramList[0], $skipParent[0])) && p('5')  && e('计划5 待定');                               // 获取产品2下的所有计划
r($planTester->getPairs($productIdList[2], $branchIdList[1], $paramList[0], $skipParent[0])) && p('16') && e('计划16 [2021-01-01 ~ 2021-06-30] / 主干');  // 获取产品6下的所有计划
r($planTester->getPairs($productIdList[2], $branchIdList[2], $paramList[0], $skipParent[0])) && p('17') && e('计划17 [2021-06-01 ~ 2021-06-15] / 分支2'); // 获取产品6下分支2的所有计划
r($planTester->getPairs($productIdList[0], $branchIdList[0], $paramList[1], $skipParent[0])) && p()     && e('0');                                        // 获取产品1下的所有未过期计划
r($planTester->getPairs($productIdList[1], $branchIdList[0], $paramList[1], $skipParent[0])) && p('5')  && e('计划5 待定');                               // 获取产品2下的所有未过期计划
r($planTester->getPairs($productIdList[2], $branchIdList[0], $paramList[1], $skipParent[0])) && p()     && e('0');                                        // 获取产品6下的所有未过期计划
r($planTester->getPairs($productIdList[0], $branchIdList[0], $paramList[2], $skipParent[0])) && p('3')  && e('计划3 [2022-01-01 ~ 2022-01-30]');          // 获取产品1下的所有未过期计划
r($planTester->getPairs($productIdList[1], $branchIdList[0], $paramList[2], $skipParent[0])) && p('5')  && e('计划5 待定');                               // 获取产品2下的所有未过期计划
r($planTester->getPairs($productIdList[2], $branchIdList[0], $paramList[2], $skipParent[0])) && p('18') && e('计划18 [2022-01-01 ~ 2022-01-30] / 主干');  // 获取产品6下的所有未过期计划
r($planTester->getPairs($productIdList[0], $branchIdList[0], $paramList[0], $skipParent[1])) && p('3')  && e('计划3 [2022-01-01 ~ 2022-01-30]');          // 获取产品1下的所有非父计划
r($planTester->getPairs($productIdList[1], $branchIdList[0], $paramList[0], $skipParent[1])) && p('5')  && e('计划5 待定');                               // 获取产品2下的所有非父计划
r($planTester->getPairs($productIdList[2], $branchIdList[0], $paramList[0], $skipParent[1])) && p('16') && e('计划16 [2021-01-01 ~ 2021-06-30] / 主干');  // 获取产品6下的所有非父计划
