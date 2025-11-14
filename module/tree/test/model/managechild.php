#!/usr/bin/env php
<?php

/**

title=测试 treeModel->manageChild();
timeout=0
cid=19389

- 查看结果数量 @4
- 查看结果数量 @2
- 查看结果数量 @4
- 查看结果
 - 第0条的id属性 @6
 - 第0条的name属性 @修改名称的产品子模块1
- 查看结果
 - 第0条的id属性 @33
 - 第0条的name属性 @新建产品子模块3
- 查看结果
 - 第0条的id属性 @9
 - 第0条的name属性 @修改名称的产品子模块81

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';
su('admin');

zenData('module')->loadYaml('module')->gen(30);

$root           = array(1, 2, 1);
$parentModuleID = array(1, 11, 4);
$branch         = array(0, 0, 0, 0, 0);

$modules1 = array('id6' => '修改名称的产品子模块1', 'id26' => '产品子模块2', '0' => '新建产品子模块1', '2' => '', '3' => '新建产品子模块2', '4' => '', '5' => '');
$short1   = array('id6' => '模块简称1', 'id26' => '修改简称的模块简称2', '0' => '新建模块简称1', '2' => '新建模块简称3', '3' => '新建模块简称2', '4' => '', '5' => '');
$order1   = array('id6' => 10, 'id26' => 20);
$tree1    = array('maxOrder' => 20, 'parentModuleID' => $parentModuleID[0], 'modules' => $modules1, 'shorts' => $short1, 'order' => $order1);

$modules2 = array('0' => '新建产品子模块3', '2' => '', '3' => '新建产品子模块4', '4' => '', '5' => '');
$short2   = array('0' => '新建模块简称3', '2' => '新建模块简称5', '3' => '新建模块简称4', '4' => '', '5' => '');
$tree2    = array('maxOrder' => 20, 'parentModuleID' => $parentModuleID[1], 'modules' => $modules2, 'shorts' => $short2);

$modules3 = array('id9' => '修改名称的产品子模块81', 'id29' => '产品子模块82', '0' => '新建产品子模块5', '2' => '', '3' => '新建产品子模块6', '4' => '', '5' => '');
$short3   = array('id9' => '模块简称81', 'id29' => '修改简称的模块简称82', '0' => '新建模块简称5', '2' => '新建模块简称7', '3' => '新建模块简称6', '4' => '', '5' => '');
$order3   = array('id9' => 10, 'id29' => 20);
$tree3    = array('maxOrder' => 20, 'parentModuleID' => $parentModuleID[2], 'modules' => $modules3, 'shorts' => $short3, 'order' => $order3);

$tree = new treeTest();

$result1 = $tree->manageChildTest($root[0], 'story', $tree1);
$result2 = $tree->manageChildTest($root[0], 'story', $tree2);
$result3 = $tree->manageChildTest($root[0], 'bug',  $tree3);

r(count($result1)) && p() && e(4); // 查看结果数量
r(count($result2)) && p() && e(2); // 查看结果数量
r(count($result3)) && p() && e(4); // 查看结果数量

r($result1) && p('0:id,name') && e('6,修改名称的产品子模块1'); // 查看结果
r($result2) && p('0:id,name') && e('33,新建产品子模块3'); // 查看结果
r($result3) && p('0:id,name') && e('9,修改名称的产品子模块81'); // 查看结果