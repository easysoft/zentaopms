#!/usr/bin/env php
<?php
/**

title=测试 productplanTao->getPlanList()
timeout=0
cid=17654

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('productplan')->loadYaml('productplan')->gen(20);

global $tester, $app;
$app->rawModule  = 'productplan';
$app->rawMethod  = 'browse';
$app->moduleName = 'productplan';
$app->methodName = 'browse';
$productplan = $tester->loadModel('productplan');

r($productplan->getPlanList(array()))                               && p()          && e('0');       // 获取空数据
r($productplan->getPlanList(array(1), '0', 'undone'))               && p('1:title') && e('计划1');   // 获取product=1的未完成的计划
r($productplan->getPlanList(array(1), '0', 'undone', 'skipparent')) && p('2:title') && e('计划2');   // 获取product=1的所有的非父计划
r($productplan->getPlanList(array(1), '1', 'all'))                  && p()          && e('0');       // 获取product=1的分支是1的计划
r($productplan->getPlanList(array(1), '0', 'wait'))                 && p('1:title') && e('计划1'); // 获取product=1的所有未开始的计划
