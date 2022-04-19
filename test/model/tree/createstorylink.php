#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->createStoryLink();
cid=1
pid=1

测试获取module 1821 project 11 product 1 的 story link >> projectstory title='产品模块1'
测试获取module 1822 project 11 product 1 的 story link >> projectstory title='产品模块2'
测试获取module 1981 project 2 product 2 的 story link >> projectstory title='产品模块161'
测试获取module 1982 product 1 的 story link >> product title='产品模块162'
测试获取module 1621 execution 101 的 story link >> execution title='模块1601'
测试获取module 1622 execution 102 的 story link >> execution title='模块1602'
测试获取module 21 branch 0 的 story link >> product title='模块1'
测试获取module 22 branch 1 的 story link >> product title='模块2'
测试获取module 1821 branch 2 的 story link >> product title='产品模块1'

*/
$moduleID    = array(1821, 1822, 1981, 1982, 1621, 1622, 21, 22);
$projectID   = array(11,12);
$productID   = array(1, 2);
$executionID = array(101, 102);
$branchID    = array(0, 1, 2);

$extra1 = array('branchID' => $branchID[0], 'projectID' => $projectID[0]);
$extra2 = array('branchID' => $branchID[0], 'projectID' => $projectID[0], 'productID' => $productID[0]);
$extra3 = array('branchID' => $branchID[0], 'projectID' => $projectID[1], 'productID' => $productID[1]);
$extra4 = array('branchID' => $branchID[0], 'projectID' => array(), 'productID' => $productID[0]);
$extra5 = array('branchID' => $branchID[0], 'executionID' => $executionID[0]);
$extra6 = array('branchID' => $branchID[0], 'executionID' => $executionID[1]);
$extra7 = array('branchID' => $branchID[0]);
$extra8 = array('branchID' => $branchID[1]);
$extra9 = array('branchID' => $branchID[2]);

$tree = new treeTest();

r($tree->createStoryLinkTest($moduleID[0], $extra1)) && p() && e("projectstory title='产品模块1'");   // 测试获取module 1821 project 11 product 1 的 story link
r($tree->createStoryLinkTest($moduleID[1], $extra2)) && p() && e("projectstory title='产品模块2'");   // 测试获取module 1822 project 11 product 1 的 story link
r($tree->createStoryLinkTest($moduleID[2], $extra3)) && p() && e("projectstory title='产品模块161'"); // 测试获取module 1981 project 2 product 2 的 story link
r($tree->createStoryLinkTest($moduleID[3], $extra4)) && p() && e("product title='产品模块162'");      // 测试获取module 1982 product 1 的 story link
r($tree->createStoryLinkTest($moduleID[4], $extra5)) && p() && e("execution title='模块1601'");       // 测试获取module 1621 execution 101 的 story link
r($tree->createStoryLinkTest($moduleID[5], $extra6)) && p() && e("execution title='模块1602'");       // 测试获取module 1622 execution 102 的 story link
r($tree->createStoryLinkTest($moduleID[6], $extra7)) && p() && e("product title='模块1'");            // 测试获取module 21 branch 0 的 story link
r($tree->createStoryLinkTest($moduleID[7], $extra8)) && p() && e("product title='模块2'");            // 测试获取module 22 branch 1 的 story link
r($tree->createStoryLinkTest($moduleID[0], $extra9)) && p() && e("product title='产品模块1'");        // 测试获取module 1821 branch 2 的 story link