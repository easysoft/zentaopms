#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->manageChild();
cid=1
pid=1

测试管理module 1821 的子模块 >> ,修改名称的产品子模块1,产品子模块2,新建产品子模块1,新建产品子模块2
测试管理module 2621 的子模块 >> ,新建产品子模块3,新建产品子模块4
测试管理module 1981 的子模块 >> ,修改名称的产品子模块81,产品子模块82,新建产品子模块5,新建产品子模块6
测试管理module 1982 的子模块 >> ,新建产品子模块7,新建产品子模块8

*/
$root           = array(1, 41);
$parentModuleID = array(1821, 2621, 1981, 1982);
$branch         = array(0, 0, 0, 0, 0);
$type           = 'story';

$modules1 = array('id2621' => '修改名称的产品子模块1', 'id2622' => '产品子模块2', '0' => '新建产品子模块1', '2' => '', '3' => '新建产品子模块2', '4' => '', '5' => '');
$short1   = array('id2621' => '模块简称1', 'id2622' => '修改简称的模块简称2', '0' => '新建模块简称1', '2' => '新建模块简称3', '3' => '新建模块简称2', '4' => '', '5' => '');
$order1   = array('id2621' => 10, 'id2622' => 20);
$tree1    = array('maxOrder' => 20, 'parentModuleID' => $parentModuleID[0], 'modules' => $modules1, 'shorts' => $short1, 'order' => $order1);

$modules2 = array('0' => '新建产品子模块3', '2' => '', '3' => '新建产品子模块4', '4' => '', '5' => '');
$short2   = array('0' => '新建模块简称3', '2' => '新建模块简称5', '3' => '新建模块简称4', '4' => '', '5' => '');
$tree2    = array('maxOrder' => 20, 'parentModuleID' => $parentModuleID[1], 'modules' => $modules2, 'shorts' => $short2);

$modules3 = array('id2701' => '修改名称的产品子模块81', 'id2702' => '产品子模块82', '0' => '新建产品子模块5', '2' => '', '3' => '新建产品子模块6', '4' => '', '5' => '');
$short3   = array('id2701' => '模块简称81', 'id2702' => '修改简称的模块简称82', '0' => '新建模块简称5', '2' => '新建模块简称7', '3' => '新建模块简称6', '4' => '', '5' => '');
$order3   = array('id2701' => 10, 'id2702' => 20);
$branch3  = array(0, 0, 0, 0, 0, 0);
$tree3    = array('maxOrder' => 20, 'parentModuleID' => $parentModuleID[2], 'modules' => $modules3, 'shorts' => $short3, 'order' => $order3, 'branch' => $branch3);

$modules4 = array('0' => '新建产品子模块7', '2' => '', '3' => '新建产品子模块8', '4' => '', '5' => '');
$short4   = array('0' => '新建模块简称7', '2' => '新建模块简称9', '3' => '新建模块简称8', '4' => '', '5' => '');
$branch4  = array(1, 1, 1, 1, 1, 1);
$tree4    = array('maxOrder' => 20, 'parentModuleID' => $parentModuleID[3], 'modules' => $modules4, 'shorts' => $short4, 'branch' => $branch4);

$tree = new treeTest();

r($tree->manageChildTest($root[0], $type, $tree1)) && p() && e(',修改名称的产品子模块1,产品子模块2,新建产品子模块1,新建产品子模块2');   // 测试管理module 1821 的子模块
r($tree->manageChildTest($root[0], $type, $tree2)) && p() && e(',新建产品子模块3,新建产品子模块4');                                     // 测试管理module 2621 的子模块
r($tree->manageChildTest($root[1], $type, $tree3)) && p() && e(',修改名称的产品子模块81,产品子模块82,新建产品子模块5,新建产品子模块6'); // 测试管理module 1981 的子模块
r($tree->manageChildTest($root[1], $type, $tree4)) && p() && e(',新建产品子模块7,新建产品子模块8');                                     // 测试管理module 1982 的子模块
