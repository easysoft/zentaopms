#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->createTaskManageLink();
cid=1
pid=1

测试获取module 1821  product 1 execution 101 的 story manage link >> 产品模块1 browsetask 子模块
测试获取module 1822  product 1 execution 101 的 story manage link >> 产品模块2 browsetask 子模块
测试获取module 1981  product 1 execution 101 的 story manage link >> 产品模块161 browsetask 子模块
测试获取module 1982  product 1 execution 101 的 story manage link >> 产品模块162 browsetask 子模块
测试获取module 1621  product 1 execution 101 的 story manage link >> 模块1601 [T] edit 编辑模块 browsetask 子模块 delete 删除
测试获取module 1622  product 1 execution 101 的 story manage link >> 模块1602 [T] edit 编辑模块 browsetask 子模块 delete 删除
测试获取module 21 product 1 execution 101 的 story manage link >> 模块1 [T] edit 编辑模块 browsetask 子模块 delete 删除
测试获取module 22 product 1 execution 101 的 story manage link >> 模块2 [T] edit 编辑模块 browsetask 子模块 delete 删除
测试获取module 1821  product 2 execution 102 的 story manage link >> 产品模块1 browsetask 子模块
测试获取module 1822  product 2 execution 102 的 story manage link >> 产品模块2 browsetask 子模块
测试获取module 1981  product 2 execution 102 的 story manage link >> 产品模块161 browsetask 子模块
测试获取module 1982  product 2 execution 102 的 story manage link >> 产品模块162 browsetask 子模块
测试获取module 1621  product 2 execution 102 的 story manage link >> 模块1601 [T] edit 编辑模块 browsetask 子模块 delete 删除
测试获取module 1622  product 2 execution 102 的 story manage link >> 模块1602 [T] edit 编辑模块 browsetask 子模块 delete 删除
测试获取module 21 product 2 execution 102 的 story manage link >> 模块1 [T] edit 编辑模块 browsetask 子模块 delete 删除
测试获取module 22 product 2 execution 102 的 story manage link >> 模块2 [T] edit 编辑模块 browsetask 子模块 delete 删除
测试获取module 1821  product 1 execution 101 tip null 的 story manage link >> 产品模块1 browsetask 子模块
测试获取module 1822  product 1 execution 101 tip null 的 story manage link >> 产品模块2 browsetask 子模块
测试获取module 21 product 1 execution 101 tip null的 story manage link >> 模块1 edit 编辑模块 browsetask 子模块 delete 删除
测试获取module 22 product 1 execution 101 tip null的 story manage link >> 模块2 edit 编辑模块 browsetask 子模块 delete 删除
测试获取module 1821  product 2 execution 102 tip null的 story manage link >> 产品模块1 browsetask 子模块
测试获取module 1822  product 2 execution 102 tip null的 story manage link >> 产品模块2 browsetask 子模块
测试获取module 21 product 2 execution 102 tip null 的 story manage link >> 模块1 edit 编辑模块 browsetask 子模块 delete 删除
测试获取module 22 product 2 execution 102 tip null 的 story manage link >> 模块2 edit 编辑模块 browsetask 子模块 delete 删除

*/
$moduleID    = array(1821, 1822, 1981, 1982, 1621, 1622, 21, 22);
$productID   = array(1, 2);
$executionID = array(101, 102);
$tip         = 'tip';

$extra1 = array('productID' => $productID[0], 'executionID' => $executionID[0], 'tip' => $tip);
$extra2 = array('productID' => $productID[1], 'executionID' => $executionID[1], 'tip' => $tip);
$extra3 = array('productID' => $productID[0], 'executionID' => $executionID[0], 'tip' => null);
$extra4 = array('productID' => $productID[1], 'executionID' => $executionID[1], 'tip' => null);

$tree = new treeTest();

r($tree->createTaskManageLinkTest($moduleID[0], $extra1)) && p() && e("产品模块1 browsetask 子模块");                              // 测试获取module 1821  product 1 execution 101 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[1], $extra1)) && p() && e("产品模块2 browsetask 子模块");                              // 测试获取module 1822  product 1 execution 101 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[2], $extra1)) && p() && e("产品模块161 browsetask 子模块");                            // 测试获取module 1981  product 1 execution 101 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[3], $extra1)) && p() && e("产品模块162 browsetask 子模块");                            // 测试获取module 1982  product 1 execution 101 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[4], $extra1)) && p() && e("模块1601 [T] edit 编辑模块 browsetask 子模块 delete 删除"); // 测试获取module 1621  product 1 execution 101 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[5], $extra1)) && p() && e("模块1602 [T] edit 编辑模块 browsetask 子模块 delete 删除"); // 测试获取module 1622  product 1 execution 101 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[6], $extra1)) && p() && e("模块1 [T] edit 编辑模块 browsetask 子模块 delete 删除");    // 测试获取module 21 product 1 execution 101 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[7], $extra1)) && p() && e("模块2 [T] edit 编辑模块 browsetask 子模块 delete 删除");    // 测试获取module 22 product 1 execution 101 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[0], $extra2)) && p() && e("产品模块1 browsetask 子模块");                              // 测试获取module 1821  product 2 execution 102 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[1], $extra2)) && p() && e("产品模块2 browsetask 子模块");                              // 测试获取module 1822  product 2 execution 102 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[2], $extra2)) && p() && e("产品模块161 browsetask 子模块");                            // 测试获取module 1981  product 2 execution 102 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[3], $extra2)) && p() && e("产品模块162 browsetask 子模块");                            // 测试获取module 1982  product 2 execution 102 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[4], $extra2)) && p() && e("模块1601 [T] edit 编辑模块 browsetask 子模块 delete 删除"); // 测试获取module 1621  product 2 execution 102 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[5], $extra2)) && p() && e("模块1602 [T] edit 编辑模块 browsetask 子模块 delete 删除"); // 测试获取module 1622  product 2 execution 102 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[6], $extra2)) && p() && e("模块1 [T] edit 编辑模块 browsetask 子模块 delete 删除");    // 测试获取module 21 product 2 execution 102 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[7], $extra2)) && p() && e("模块2 [T] edit 编辑模块 browsetask 子模块 delete 删除");    // 测试获取module 22 product 2 execution 102 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[0], $extra3)) && p() && e("产品模块1 browsetask 子模块");                              // 测试获取module 1821  product 1 execution 101 tip null 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[1], $extra3)) && p() && e("产品模块2 browsetask 子模块");                              // 测试获取module 1822  product 1 execution 101 tip null 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[6], $extra3)) && p() && e("模块1 edit 编辑模块 browsetask 子模块 delete 删除");        // 测试获取module 21 product 1 execution 101 tip null的 story manage link
r($tree->createTaskManageLinkTest($moduleID[7], $extra3)) && p() && e("模块2 edit 编辑模块 browsetask 子模块 delete 删除");        // 测试获取module 22 product 1 execution 101 tip null的 story manage link
r($tree->createTaskManageLinkTest($moduleID[0], $extra4)) && p() && e("产品模块1 browsetask 子模块");                              // 测试获取module 1821  product 2 execution 102 tip null的 story manage link
r($tree->createTaskManageLinkTest($moduleID[1], $extra4)) && p() && e("产品模块2 browsetask 子模块");                              // 测试获取module 1822  product 2 execution 102 tip null的 story manage link
r($tree->createTaskManageLinkTest($moduleID[6], $extra4)) && p() && e("模块1 edit 编辑模块 browsetask 子模块 delete 删除");        // 测试获取module 21 product 2 execution 102 tip null 的 story manage link
r($tree->createTaskManageLinkTest($moduleID[7], $extra4)) && p() && e("模块2 edit 编辑模块 browsetask 子模块 delete 删除");        // 测试获取module 22 product 2 execution 102 tip null 的 story manage link