#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->isMergeModule();
cid=1
pid=1

测试检查root 1 type bug 合并模块版本 >> 1
测试检查root 2 type bug 合并模块版本 >> 1
测试检查root 3 type bug 合并模块版本 >> 1
测试检查root 1 type case 合并模块版本 >> 1
测试检查root 2 type case 合并模块版本 >> 1
测试检查root 3 type case 合并模块版本 >> 1
测试检查root 101 type task 合并模块版本 >> 1
测试检查root 102 type task 合并模块版本 >> 1
测试检查root 103 type task 合并模块版本 >> 1

*/
$root = array(1, 2, 3, 101, 102, 103);
$type = array('bug', 'case', 'task');

$tree = new treeTest();

r($tree->isMergeModuleTest($root[0], $type[0])) && p() && e('1'); // 测试检查root 1 type bug 合并模块版本
r($tree->isMergeModuleTest($root[1], $type[0])) && p() && e('1'); // 测试检查root 2 type bug 合并模块版本
r($tree->isMergeModuleTest($root[2], $type[0])) && p() && e('1'); // 测试检查root 3 type bug 合并模块版本
r($tree->isMergeModuleTest($root[0], $type[1])) && p() && e('1'); // 测试检查root 1 type case 合并模块版本
r($tree->isMergeModuleTest($root[1], $type[1])) && p() && e('1'); // 测试检查root 2 type case 合并模块版本
r($tree->isMergeModuleTest($root[2], $type[1])) && p() && e('1'); // 测试检查root 3 type case 合并模块版本
r($tree->isMergeModuleTest($root[3], $type[2])) && p() && e('1'); // 测试检查root 101 type task 合并模块版本
r($tree->isMergeModuleTest($root[4], $type[2])) && p() && e('1'); // 测试检查root 102 type task 合并模块版本
r($tree->isMergeModuleTest($root[5], $type[2])) && p() && e('1'); // 测试检查root 103 type task 合并模块版本