#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getProjectStoryTreeMenu();
timeout=0
cid=1

- 测试获取项目1的Story模块 @0
- 测试不存在项目的Story模块 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

zdTable('module')->config('module')->gen(20);

$tree = new treeTest();

r($tree->getProjectStoryTreeMenuTest(1))  && p() && e('0');  // 测试获取项目1的Story模块
r($tree->getProjectStoryTreeMenuTest(10)) && p() && e('0');  // 测试不存在项目的Story模块
