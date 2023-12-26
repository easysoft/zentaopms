#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getTaskTreeMenu();
timeout=0
cid=1

- 测试获取项目1的task模块 @1|11
- 测试获取项目1的task模块 @16

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

zdTable('module')->config('module')->gen(20);

$tree = new treeTest();

r($tree->getTaskTreeMenuTest(1))  && p() && e('1|11');  // 测试获取项目1的task模块
r($tree->getTaskTreeMenuTest(41)) && p() && e('16');    // 测试获取项目1的task模块
