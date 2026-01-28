#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getCaseTreeMenu();
timeout=0
cid=19365

- 测试获取产品1的Case模块 @正常产品1
- 测试产品10的Case模块 @正常产品10
- 测试获取产品1的Case模块 @正常产品2
- 测试获取产品1的Case模块 @正常产品3
- 测试不存在产品的Case模块 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$moduleTable = zenData('module')->loadYaml('module');
$moduleTable->root->range('1');
$moduleTable->type->range('case');
$moduleTable->gen(3);
zenData('product')->loadYaml('product')->gen(20);
zenData('branch')->gen(0);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1-100');
$projectproduct->gen(20);

$projectcase = zenData('projectcase')->loadYaml('projectcase');
$projectcase->project->range('1-100');
$projectcase->case->range('1-100');
$projectcase->gen(20);

$case = zenData('case')->loadYaml('case');
$case->module->range('5');
$case->product->range('1');
$case->project->range('1');
$case->gen(20);

$tree = new treeModelTest();

r($tree->getCaseTreeMenuTest(1, 1))   && p() && e('正常产品1');  // 测试获取产品1的Case模块
r($tree->getCaseTreeMenuTest(2, 2))   && p() && e('正常产品2');  // 测试获取产品1的Case模块
r($tree->getCaseTreeMenuTest(3, 2))   && p() && e('正常产品3');  // 测试获取产品1的Case模块
r($tree->getCaseTreeMenuTest(10, 10)) && p() && e('正常产品10'); // 测试产品10的Case模块
r($tree->getCaseTreeMenuTest(30, 30)) && p() && e('0');          // 测试不存在产品的Case模块
