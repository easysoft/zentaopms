#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getBugTreeMenu();
timeout=0
cid=1

- 测试获取产品1的Bug模块 @正常产品1
- 测试不存在产品的Bug模块 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

zdTable('module')->config('module')->gen(20);

$tree = new treeTest();

r($tree->getBugTreeMenuTest(1))  && p() && e('正常产品1');  // 测试获取产品1的Bug模块
r($tree->getBugTreeMenuTest(10)) && p() && e('0');          // 测试不存在产品的Bug模块