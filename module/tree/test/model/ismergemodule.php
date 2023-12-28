#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

$product = zdTable('product');
$product->createdVersion->range('4.0,12.5.3{30}');
$product->gen(10);
$project = zdTable('project');
$project->id->range('1-50');
$project->openedVersion->range('4.0,12.5.3{30}');
$project->gen(10);

/**

title=测试 treeModel->isMergeModule();
timeout=0
cid=1

- 测试检查root 1 type bug 合并模块版本 @2
- 测试检查root 2 type bug 合并模块版本 @1
- 测试检查root 3 type bug 合并模块版本 @1
- 测试检查root 1 type case 合并模块版本 @2
- 测试检查root 2 type case 合并模块版本 @1
- 测试检查root 3 type case 合并模块版本 @1
- 测试检查root 4 type task 合并模块版本 @2

*/
$root = array(1, 2, 3, 4);
$type = array('bug', 'case', 'task');

$tree = new treeTest();

r($tree->isMergeModuleTest($root[0], $type[0])) && p() && e('2'); // 测试检查root 1 type bug 合并模块版本
r($tree->isMergeModuleTest($root[1], $type[0])) && p() && e('1'); // 测试检查root 2 type bug 合并模块版本
r($tree->isMergeModuleTest($root[2], $type[0])) && p() && e('1'); // 测试检查root 3 type bug 合并模块版本
r($tree->isMergeModuleTest($root[0], $type[1])) && p() && e('2'); // 测试检查root 1 type case 合并模块版本
r($tree->isMergeModuleTest($root[1], $type[1])) && p() && e('1'); // 测试检查root 2 type case 合并模块版本
r($tree->isMergeModuleTest($root[2], $type[1])) && p() && e('1'); // 测试检查root 3 type case 合并模块版本
r($tree->isMergeModuleTest($root[0], $type[2])) && p() && e('2'); // 测试检查root 4 type task 合并模块版本