#!/usr/bin/env php
<?php

/**

title=测试 treeModel->updateOrder();
timeout=0
cid=1

- 测试更新story模块顺序 @,12,32,16

- 测试更新task模块顺序 @,18,31,11

- 测试更新story不同分支模块顺序 @,2,32,12

- 测试更新task不同分支模块顺序 @,1,31,11

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(100);

$orders1 = array('12' => 1, '16' => 4, '32' => 2);
$orders2 = array('11' => 7, '18' => 2, '31' => 5);
$orders3 = array('12' => 7, '2' => 6,  '32' => 5); // 2是branch0， 12 32是分支1
$orders4 = array('11' => 7, '1' => 6,  '31' => 5); // 1是branch0， 11 31是分支1

$tree = new treeTest();

r($tree->updateOrderTest($orders1)) && p() && e(',12,32,16'); // 测试更新story模块顺序
r($tree->updateOrderTest($orders2)) && p() && e(',18,31,11'); // 测试更新task模块顺序
r($tree->updateOrderTest($orders3)) && p() && e(',2,32,12');  // 测试更新story不同分支模块顺序
r($tree->updateOrderTest($orders4)) && p() && e(',1,31,11');  // 测试更新task不同分支模块顺序