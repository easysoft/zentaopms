#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->createTaskLink();
cid=1
pid=1

测试获取module 1821  product 1 execution 101 的 story link >> 101 1821 title='产品模块1'
测试获取module 1822  product 1 execution 101 的 story link >> 101 1822 title='产品模块2'
测试获取module 1981  product 1 execution 101 的 story link >> 101 1981 title='产品模块161'
测试获取module 1982  product 1 execution 101 的 story link >> 101 1982 title='产品模块162'
测试获取module 1621  product 1 execution 101 的 story link >> 101 1621 title='模块1601'
测试获取module 1622  product 1 execution 101 的 story link >> 101 1622 title='模块1602'
测试获取module 21 product 1 execution 101 的 story link >> 101 21 title='模块1'
测试获取module 22 product 1 execution 101 的 story link >> 101 22 title='模块2'
测试获取module 1821  product 2 execution 102 的 story link >> 102 1821 title='产品模块1'
测试获取module 1822  product 2 execution 102 的 story link >> 102 1822 title='产品模块2'
测试获取module 1981  product 2 execution 102 的 story link >> 102 1981 title='产品模块161'
测试获取module 1982  product 2 execution 102 的 story link >> 102 1982 title='产品模块162'
测试获取module 1621  product 2 execution 102 的 story link >> 102 1621 title='模块1601'
测试获取module 1622  product 2 execution 102 的 story link >> 102 1622 title='模块1602'
测试获取module 21 product 2 execution 102 的 story link >> 102 21 title='模块1'
测试获取module 22 product 2 execution 102 的 story link >> 102 22 title='模块2'

*/
$moduleID    = array(1821, 1822, 1981, 1982, 1621, 1622, 21, 22);
$productID   = array(1, 2);
$executionID = array(101, 102);

$extra1 = array('productID' => $productID[0], 'executionID' => $executionID[0]);
$extra2 = array('productID' => $productID[1], 'executionID' => $executionID[1]);

$tree = new treeTest();

r($tree->createTaskLinkTest($moduleID[0], $extra1)) && p() && e("101 1821 title='产品模块1'");   // 测试获取module 1821  product 1 execution 101 的 story link
r($tree->createTaskLinkTest($moduleID[1], $extra1)) && p() && e("101 1822 title='产品模块2'");   // 测试获取module 1822  product 1 execution 101 的 story link
r($tree->createTaskLinkTest($moduleID[2], $extra1)) && p() && e("101 1981 title='产品模块161'"); // 测试获取module 1981  product 1 execution 101 的 story link
r($tree->createTaskLinkTest($moduleID[3], $extra1)) && p() && e("101 1982 title='产品模块162'"); // 测试获取module 1982  product 1 execution 101 的 story link
r($tree->createTaskLinkTest($moduleID[4], $extra1)) && p() && e("101 1621 title='模块1601'");    // 测试获取module 1621  product 1 execution 101 的 story link
r($tree->createTaskLinkTest($moduleID[5], $extra1)) && p() && e("101 1622 title='模块1602'");    // 测试获取module 1622  product 1 execution 101 的 story link
r($tree->createTaskLinkTest($moduleID[6], $extra1)) && p() && e("101 21 title='模块1'");         // 测试获取module 21 product 1 execution 101 的 story link
r($tree->createTaskLinkTest($moduleID[7], $extra1)) && p() && e("101 22 title='模块2'");         // 测试获取module 22 product 1 execution 101 的 story link
r($tree->createTaskLinkTest($moduleID[0], $extra2)) && p() && e("102 1821 title='产品模块1'");   // 测试获取module 1821  product 2 execution 102 的 story link
r($tree->createTaskLinkTest($moduleID[1], $extra2)) && p() && e("102 1822 title='产品模块2'");   // 测试获取module 1822  product 2 execution 102 的 story link
r($tree->createTaskLinkTest($moduleID[2], $extra2)) && p() && e("102 1981 title='产品模块161'"); // 测试获取module 1981  product 2 execution 102 的 story link
r($tree->createTaskLinkTest($moduleID[3], $extra2)) && p() && e("102 1982 title='产品模块162'"); // 测试获取module 1982  product 2 execution 102 的 story link
r($tree->createTaskLinkTest($moduleID[4], $extra2)) && p() && e("102 1621 title='模块1601'");    // 测试获取module 1621  product 2 execution 102 的 story link
r($tree->createTaskLinkTest($moduleID[5], $extra2)) && p() && e("102 1622 title='模块1602'");    // 测试获取module 1622  product 2 execution 102 的 story link
r($tree->createTaskLinkTest($moduleID[6], $extra2)) && p() && e("102 21 title='模块1'");         // 测试获取module 21 product 2 execution 102 的 story link
r($tree->createTaskLinkTest($moduleID[7], $extra2)) && p() && e("102 22 title='模块2'");         // 测试获取module 22 product 2 execution 102 的 story link