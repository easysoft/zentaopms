#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->createManageLink();
cid=1
pid=1

测试创建module 1821 branch 0 的managelink >> 产品模块1 <
测试创建module 1822 branch 0 的managelink >> 产品模块2 <
测试创建module 1981 branch 0 的managelink >> 产品模块161
测试创建module 1981 branch 1 的managelink >> 产品模块161
测试创建module 1981 branch 2 的managelink >> 产品模块161
测试创建module 1982 branch 0 的managelink >> 产品模块162
测试创建module 1982 branch 1 的managelink >> 产品模块162
测试创建module 1982 branch 2 的managelink >> 产品模块162
测试创建module 1621 branch 0 的managelink >> 模块1601 <a h
测试创建module 1622 branch 0 的managelink >> 模块1602 <a h
测试创建module 21 branch 0 的managelink >> 模块1 <a href
测试创建module 22 branch 0 的managelink >> 模块2 <a href

*/
$moduleID = array(1821, 1822, 1981, 1982, 1621, 1622, 21, 22);
$extra0   = array('branchID' => 0);
$extra1   = array('branchID' => 1);
$extra2   = array('branchID' => 2);

$tree = new treeTest();

r($tree->createManageLinkTest($moduleID[0], $extra0)) && p() && e("产品模块1 <");   // 测试创建module 1821 branch 0 的managelink
r($tree->createManageLinkTest($moduleID[1], $extra0)) && p() && e("产品模块2 <");   // 测试创建module 1822 branch 0 的managelink
r($tree->createManageLinkTest($moduleID[2], $extra0)) && p() && e("产品模块161");   // 测试创建module 1981 branch 0 的managelink
r($tree->createManageLinkTest($moduleID[2], $extra1)) && p() && e("产品模块161");   // 测试创建module 1981 branch 1 的managelink
r($tree->createManageLinkTest($moduleID[2], $extra2)) && p() && e("产品模块161");   // 测试创建module 1981 branch 2 的managelink
r($tree->createManageLinkTest($moduleID[3], $extra0)) && p() && e("产品模块162");   // 测试创建module 1982 branch 0 的managelink
r($tree->createManageLinkTest($moduleID[3], $extra1)) && p() && e("产品模块162");   // 测试创建module 1982 branch 1 的managelink
r($tree->createManageLinkTest($moduleID[3], $extra2)) && p() && e("产品模块162");   // 测试创建module 1982 branch 2 的managelink
r($tree->createManageLinkTest($moduleID[4], $extra0)) && p() && e("模块1601 <a h"); // 测试创建module 1621 branch 0 的managelink
r($tree->createManageLinkTest($moduleID[5], $extra0)) && p() && e("模块1602 <a h"); // 测试创建module 1622 branch 0 的managelink
r($tree->createManageLinkTest($moduleID[6], $extra0)) && p() && e("模块1 <a href"); // 测试创建module 21 branch 0 的managelink
r($tree->createManageLinkTest($moduleID[7], $extra0)) && p() && e("模块2 <a href"); // 测试创建module 22 branch 0 的managelink