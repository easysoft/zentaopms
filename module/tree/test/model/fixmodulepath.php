#!/usr/bin/env php
<?php

/**

title=测试 treeModel->fixModulePath();
timeout=0
cid=1

- 修复root为 1 的module path
 - 属性1 @1
 - 属性6 @1+6
- 修复root为 2 的module path
 - 属性2 @2
 - 属性7 @2+7
- 修复root为 3 的module path
 - 属性3 @3
 - 属性8 @3+8
- 修复root为 4 的module path
 - 属性4 @4
 - 属性9 @4+9
- 修复root为 5 的module path
 - 属性5 @5
 - 属性10 @5+10

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

zdTable('module')->config('module')->gen(100);

$root = array(1, 2);
$type = array('task', 'story', 'doc', 'bug', 'case');

$tree = new treeTest();

r($tree->fixModulePathTest(1, $type[0])) && p('1,6') && e('1,1+6');  // 修复root为 1 的module path
r($tree->fixModulePathTest(1, $type[1])) && p('2,7')  && e('2,2+7');  // 修复root为 2 的module path
r($tree->fixModulePathTest(1, $type[2])) && p('3,8')  && e('3,3+8');  // 修复root为 3 的module path
r($tree->fixModulePathTest(1, $type[3])) && p('4,9')  && e('4,4+9');  // 修复root为 4 的module path
r($tree->fixModulePathTest(1, $type[4])) && p('5,10') && e('5,5+10'); // 修复root为 5 的module path