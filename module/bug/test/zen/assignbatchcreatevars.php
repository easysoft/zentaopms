#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignBatchCreateVars();
timeout=0
cid=15424

- 测试正常产品无执行情况 @1
- 测试正常产品有执行情况 @1
- 测试分支产品无执行情况 @1
- 测试分支产品有执行情况 @1
- 测试看板类型执行情况 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('assignbatchcreatevars/product', false, 2)->gen(5);
zenData('build')->loadYaml('assignbatchcreatevars/build', false, 2)->gen(10);
zenData('story')->loadYaml('assignbatchcreatevars/story', false, 2)->gen(20);
zenData('branch')->loadYaml('assignbatchcreatevars/branch', false, 2)->gen(10);
zenData('projectproduct')->loadYaml('assignbatchcreatevars/projectproduct', false, 2)->gen(10);
zenData('productplan')->loadYaml('assignbatchcreatevars/productplan', false, 2)->gen(10);
zenData('module')->loadYaml('assignbatchcreatevars/module', false, 2)->gen(20);

$project = zenData('project');
$project->id->range('1-20');
$project->type->range('project{5},sprint{10},kanban{5}');
$project->status->range('wait,doing{15},closed{4}');
$project->model->range('scrum{10},waterfall{5},kanban{5}');
$project->gen(20);

su('admin');

$bugTest = new bugZenTest();

$product1 = new stdclass();
$product1->id = 1;
$product1->type = 'normal';

$product2 = new stdclass();
$product2->id = 2;
$product2->type = 'branch';

$product4 = new stdclass();
$product4->id = 4;
$product4->type = 'normal';

r($bugTest->assignBatchCreateVarsTest(0, $product1, '0', array(), array())) && p() && e('1'); // 测试正常产品无执行情况
r($bugTest->assignBatchCreateVarsTest(1, $product1, '0', array(), array())) && p() && e('1'); // 测试正常产品有执行情况
r($bugTest->assignBatchCreateVarsTest(0, $product2, '0', array(), array())) && p() && e('1'); // 测试分支产品无执行情况
r($bugTest->assignBatchCreateVarsTest(1, $product2, '1', array(), array())) && p() && e('1'); // 测试分支产品有执行情况
r($bugTest->assignBatchCreateVarsTest(7, $product4, '0', array(), array())) && p() && e('1'); // 测试看板类型执行情况