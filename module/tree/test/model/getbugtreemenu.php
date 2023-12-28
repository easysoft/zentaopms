#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getBugTreeMenu();
timeout=0
cid=1

- 测试获取产品1的Bug模块 @正常产品1|模块4
- 测试获取产品10的Bug模块 @正常产品10
- 测试不存在产品的Bug模块 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

zdTable('module')->config('module')->gen(20);
zdTable('product')->gen(10);

$projectproduct = zdTable('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1-100');
$projectproduct->gen(20);

$bug = zdTable('bug');
$bug->project->range('1');
$bug->module->range('4');
$bug->gen(10);

$tree = new treeTest();

r($tree->getBugTreeMenuTest(1))  && p() && e('正常产品1|模块4'); // 测试获取产品1的Bug模块
r($tree->getBugTreeMenuTest(10)) && p() && e('正常产品10');      // 测试获取产品10的Bug模块
r($tree->getBugTreeMenuTest(20)) && p() && e('0');               // 测试不存在产品的Bug模块