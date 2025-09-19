#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::genTreeOptions();
timeout=0
cid=0

- 执行dataviewTest模块的genTreeOptionsTest方法，参数是$tree1, array 第children条的0:title属性 @Root Node
- 执行dataviewTest模块的genTreeOptionsTest方法，参数是$tree2, array 第children条的0:children:0:children:0:title属性 @Level 3
- 执行dataviewTest模块的genTreeOptionsTest方法，参数是$tree3, array 第children条的0:children:0:title属性 @New Node
- 执行dataviewTest模块的genTreeOptionsTest方法，参数是$tree4, array 第children条的0:title属性 @Branch 1
- 执行dataviewTest模块的genTreeOptionsTest方法，参数是$tree4, array 第children条的1:title属性 @Branch 2
- 执行dataviewTest模块的genTreeOptionsTest方法，参数是$tree5, array 第children条的0:children:0:value属性 @b

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dataview.unittest.class.php';

su('admin');

$dataviewTest = new dataviewTest();

$tree1 = new stdclass();
r($dataviewTest->genTreeOptionsTest($tree1, array('root' => 'Root Node'), array('root'))) && p('children:0:title') && e('Root Node');

$tree2 = new stdclass();
r($dataviewTest->genTreeOptionsTest($tree2, array('level1' => 'Level 1', 'level2' => 'Level 2', 'level3' => 'Level 3'), array('level1', 'level2', 'level3'))) && p('children:0:children:0:children:0:title') && e('Level 3');

$tree3 = new stdclass();
$tree3->children = array();
$existingChild = new stdclass();
$existingChild->title = 'Existing Node';
$existingChild->value = 'existing';
$tree3->children[] = $existingChild;
r($dataviewTest->genTreeOptionsTest($tree3, array('existing' => 'Existing Node', 'new' => 'New Node'), array('existing', 'new'))) && p('children:0:children:0:title') && e('New Node');

$tree4 = new stdclass();
r($dataviewTest->genTreeOptionsTest($tree4, array('branch1' => 'Branch 1', 'branch2' => 'Branch 2'), array('branch1'))) && p('children:0:title') && e('Branch 1');
r($dataviewTest->genTreeOptionsTest($tree4, array('branch1' => 'Branch 1', 'branch2' => 'Branch 2'), array('branch2'))) && p('children:1:title') && e('Branch 2');

$tree5 = new stdclass();
r($dataviewTest->genTreeOptionsTest($tree5, array('a' => 'Node A', 'b' => 'Node B', 'c' => 'Node C'), array('a', 'b'))) && p('children:0:children:0:value') && e('b');