#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getTaskTreeMenu();
timeout=0
cid=1

- 测试获取项目1的task模块 @6|1|11
- 测试获取项目1的task模块 @18

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

zdTable('module')->config('module')->gen(20);

$projectproduct = zdTable('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1-100');
$projectproduct->gen(100);

$tree = new treeTest();

r($tree->getTaskTreeMenuTest(1))  && p() && e('6|1|11');  // 测试获取项目1的task模块
r($tree->getTaskTreeMenuTest(41)) && p() && e('18');      // 测试获取项目1的task模块