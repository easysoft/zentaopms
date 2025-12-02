#!/usr/bin/env php
<?php
/**

title=测试planModel->getTopPlanPairs();
cid=17640
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);
su('admin');

$plan = zenData('productplan')->loadYaml('productplan');
$plan->product->range('1');
$plan->status->range('wait,doing,done,closed');
$plan->parent->range('`-1`');
$plan->gen(10);

$productIdList = array(0, 1, 2);
$statusList    = array('', 'wait', 'doing', 'done', 'closed');

global $tester, $app;
$app->rawModule  = 'productplan';
$app->rawMethod  = 'browse';
$app->moduleName = 'productplan';
$app->methodName = 'browse';
$tester->loadModel('productplan');
r($tester->productplan->getTopPlanPairs($productIdList[0], $statusList[0])) && p()    && e('0');     // 测试产品ID为空时，所有的父计划
r($tester->productplan->getTopPlanPairs($productIdList[0], $statusList[1])) && p()    && e('0');     // 测试产品ID为空时，所有不包括未开始的父计划
r($tester->productplan->getTopPlanPairs($productIdList[0], $statusList[2])) && p()    && e('0');     // 测试产品ID为空时，所有不包括进行中的父计划
r($tester->productplan->getTopPlanPairs($productIdList[0], $statusList[3])) && p()    && e('0');     // 测试产品ID为空时，所有不包括已完成的父计划
r($tester->productplan->getTopPlanPairs($productIdList[0], $statusList[4])) && p()    && e('0');     // 测试产品ID为空时，所有不包括已关闭的父计划
r($tester->productplan->getTopPlanPairs($productIdList[1], $statusList[0])) && p('1') && e('计划1'); // 测试产品ID为1时，所有的父计划
r($tester->productplan->getTopPlanPairs($productIdList[1], $statusList[1])) && p('2') && e('计划2'); // 测试产品ID为1时，所有不包括未开始的父计划
r($tester->productplan->getTopPlanPairs($productIdList[1], $statusList[2])) && p('3') && e('计划3'); // 测试产品ID为1时，所有不包括进行中的父计划
r($tester->productplan->getTopPlanPairs($productIdList[1], $statusList[3])) && p('4') && e('计划4'); // 测试产品ID为1时，所有不包括已完成的父计划
r($tester->productplan->getTopPlanPairs($productIdList[1], $statusList[4])) && p('5') && e('计划5'); // 测试产品ID为1时，所有不包括已关闭的父计划
r($tester->productplan->getTopPlanPairs($productIdList[2], $statusList[0])) && p()    && e('0');     // 测试产品ID为2时，所有的父计划
r($tester->productplan->getTopPlanPairs($productIdList[2], $statusList[1])) && p()    && e('0');     // 测试产品ID为2时，所有不包括未开始的父计划
r($tester->productplan->getTopPlanPairs($productIdList[2], $statusList[2])) && p()    && e('0');     // 测试产品ID为2时，所有不包括进行中的父计划
r($tester->productplan->getTopPlanPairs($productIdList[2], $statusList[3])) && p()    && e('0');     // 测试产品ID为2时，所有不包括已完成的父计划
r($tester->productplan->getTopPlanPairs($productIdList[2], $statusList[4])) && p()    && e('0');     // 测试产品ID为2时，所有不包括已关闭的父计划
