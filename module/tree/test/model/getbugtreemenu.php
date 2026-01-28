#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getBugTreeMenu();
timeout=0
cid=19363

- 测试获取产品1的Bug模块 @正常产品1|模块4
- 测试获取产品2的Bug模块 @正常产品2
- 测试获取产品3的Bug模块 @正常产品3
- 测试获取产品10的Bug模块 @正常产品10
- 测试不存在产品的Bug模块 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

zenData('module')->loadYaml('module')->gen(20);
zenData('product')->gen(10);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1-100');
$projectproduct->gen(20);

$bug = zenData('bug');
$bug->project->range('1');
$bug->module->range('4');
$bug->gen(10);

$tree = new treeModelTest();

r($tree->getBugTreeMenuTest(1))  && p() && e('正常产品1|模块4'); // 测试获取产品1的Bug模块
r($tree->getBugTreeMenuTest(2))  && p() && e('正常产品2'); // 测试获取产品2的Bug模块
r($tree->getBugTreeMenuTest(3))  && p() && e('正常产品3'); // 测试获取产品3的Bug模块
r($tree->getBugTreeMenuTest(10)) && p() && e('正常产品10'); // 测试获取产品10的Bug模块
r($tree->getBugTreeMenuTest(20)) && p() && e('0'); // 测试不存在产品的Bug模块