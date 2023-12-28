#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getCaseTreeMenu();
timeout=0
cid=1

- 测试获取产品1的Case模块 @正常产品1|模块5
- 测试产品10的Case模块 @正常产品10
- 测试不存在产品的Case模块 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

zdTable('module')->config('module')->gen(20);
zdTable('product')->gen(20);

$projectproduct = zdTable('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1-100');
$projectproduct->gen(20);

$projectcase = zdTable('projectcase');
$projectcase->project->range('1-100');
$projectcase->case->range('1-100');
$projectcase->gen(20);

$case = zdTable('case');
$case->module->range('5');
$case->gen(20);

$tree = new treeTest();

r($tree->getCaseTreeMenuTest(1, 1))   && p() && e('正常产品1|模块5'); // 测试获取产品1的Case模块
r($tree->getCaseTreeMenuTest(10, 10)) && p() && e('正常产品10');      // 测试产品10的Case模块
r($tree->getCaseTreeMenuTest(30, 30)) && p() && e('0');               // 测试不存在产品的Case模块