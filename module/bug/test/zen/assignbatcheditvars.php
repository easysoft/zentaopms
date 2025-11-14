#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignBatchEditVars();
timeout=0
cid=15425

- 测试正常产品批量编辑情况 @1
- 测试分支产品批量编辑情况 @1
- 测试无产品ID批量编辑情况(从"我的地盘") @1
- 测试多个产品的bug批量编辑 @1
- 测试空bug列表情况 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('assignbatcheditvars/product', false, 2)->gen(5);
zenData('bug')->loadYaml('assignbatcheditvars/bug', false, 2)->gen(20);
zenData('branch')->loadYaml('assignbatcheditvars/branch', false, 2)->gen(10);
zenData('project')->loadYaml('assignbatcheditvars/project', false, 2)->gen(10);
zenData('module')->loadYaml('assignbatcheditvars/module', false, 2)->gen(20);
zenData('productplan')->loadYaml('assignbatcheditvars/productplan', false, 2)->gen(10);
zenData('projectproduct')->loadYaml('assignbatcheditvars/projectproduct', false, 2)->gen(10);

su('admin');

$bugTest = new bugZenTest();

$_POST['bugIdList'] = array(1, 2, 3, 4, 5);
r($bugTest->assignBatchEditVarsTest(1, '0')) && p() && e('1'); // 测试正常产品批量编辑情况
unset($_POST['bugIdList']);

$_POST['bugIdList'] = array(6, 7, 8, 9, 10);
r($bugTest->assignBatchEditVarsTest(2, '1')) && p() && e('1'); // 测试分支产品批量编辑情况
unset($_POST['bugIdList']);

$_POST['bugIdList'] = array(1, 2, 6, 7, 11, 12);
r($bugTest->assignBatchEditVarsTest(0, '0')) && p() && e('1'); // 测试无产品ID批量编辑情况(从"我的地盘")
unset($_POST['bugIdList']);

$_POST['bugIdList'] = array(1, 2, 3, 6, 7, 8, 11, 12, 13);
r($bugTest->assignBatchEditVarsTest(1, '0')) && p() && e('1'); // 测试多个产品的bug批量编辑
unset($_POST['bugIdList']);

$_POST['bugIdList'] = array();
r($bugTest->assignBatchEditVarsTest(1, '0')) && p() && e('1'); // 测试空bug列表情况
unset($_POST['bugIdList']);