#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getTaskSons();
timeout=0
cid=19382

- 测试获取root 1 product 1 module 1 的子module @,6

- 测试获取root 1 product 1 module 6 的子module @0
- 测试获取root 1 product 1 module 10 的子module @0
- 测试获取root 2 product 1 module 1 的子module @0
- 测试获取root 2 product 2 module 6 的子module @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('module')->loadYaml('module')->gen(20);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1-100');
$projectproduct->gen(100);

$rootID    = array(1, 2, 41);
$productID = array(1, 2, 41);
$moduleID  = array(1, 6, 10);

$tree = new treeModelTest();

r($tree->getTaskSonsTest($rootID[0], $productID[0], $moduleID[0])) && p() && e(',6'); // 测试获取root 1 product 1 module 1 的子module
r($tree->getTaskSonsTest($rootID[0], $productID[0], $moduleID[1])) && p() && e('0');  // 测试获取root 1 product 1 module 6 的子module
r($tree->getTaskSonsTest($rootID[0], $productID[0], $moduleID[2])) && p() && e('0');  // 测试获取root 1 product 1 module 10 的子module
r($tree->getTaskSonsTest($rootID[1], $productID[0], $moduleID[0])) && p() && e('0');  // 测试获取root 2 product 1 module 1 的子module
r($tree->getTaskSonsTest($rootID[1], $productID[1], $moduleID[1])) && p() && e('0');  // 测试获取root 2 product 2 module 6 的子module