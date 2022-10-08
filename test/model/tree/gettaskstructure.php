#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getTaskStructure();
cid=1
pid=1

获取root 101 的task 结构 >> 正常产品1:2;多平台产品81:0;多平台产品91:0;模块1:1;模块2:0模块3:0
获取root 102 的task 结构 >> 正常产品2:2;多平台产品82:0;多平台产品92:0;模块4:0模块5:0模块6:0
获取root 103 的task 结构 >> 正常产品3:2;多平台产品83:0;多平台产品93:0;模块7:0模块8:0模块9:0
获取root 104 的task 结构 >> 正常产品4:2;多平台产品84:0;多平台产品94:0;模块11:0模块12:0模块10:0
获取root 105 的task 结构 >> 正常产品5:2;多平台产品85:0;多平台产品95:0;模块13:0模块14:0模块15:0
获取root 106 的task 结构 >> 正常产品6:2;多平台产品86:0;多平台产品96:0;模块16:0模块17:0模块18:0

*/
$root = array(101, 102, 103, 104, 105, 106);

$tree = new treeTest();

r($tree->getTaskStructureTest($root[0])) && p() && e('正常产品1:2;多平台产品81:0;多平台产品91:0;模块1:1;模块2:0模块3:0');   // 获取root 101 的task 结构
r($tree->getTaskStructureTest($root[1])) && p() && e('正常产品2:2;多平台产品82:0;多平台产品92:0;模块4:0模块5:0模块6:0');    // 获取root 102 的task 结构
r($tree->getTaskStructureTest($root[2])) && p() && e('正常产品3:2;多平台产品83:0;多平台产品93:0;模块7:0模块8:0模块9:0');    // 获取root 103 的task 结构
r($tree->getTaskStructureTest($root[3])) && p() && e('正常产品4:2;多平台产品84:0;多平台产品94:0;模块11:0模块12:0模块10:0'); // 获取root 104 的task 结构
r($tree->getTaskStructureTest($root[4])) && p() && e('正常产品5:2;多平台产品85:0;多平台产品95:0;模块13:0模块14:0模块15:0'); // 获取root 105 的task 结构
r($tree->getTaskStructureTest($root[5])) && p() && e('正常产品6:2;多平台产品86:0;多平台产品96:0;模块16:0模块17:0模块18:0'); // 获取root 106 的task 结构