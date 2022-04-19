#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getOptionMenu();
cid=1
pid=1

测试获取root 1 type story 的目录 >> 13
测试获取root 2 type story 的目录 >> 13
测试获取root 41 type story 的目录 >> 11
测试获取root 42 type story 的目录 >> 11
测试获取root 101 type task 的目录 >> 5
测试获取root 102 type task 的目录 >> 4
测试获取root 1 type doc 的目录 >> 4
测试获取root 2 type doc 的目录 >> 4

*/
$root = array(1, 2, 41, 42, 101, 102);
$type = array('story', 'task', 'doc');

$tree = new treeTest();

r($tree->getOptionMenuTest($root[0], $type[0])) && p() && e('13'); // 测试获取root 1 type story 的目录
r($tree->getOptionMenuTest($root[1], $type[0])) && p() && e('13'); // 测试获取root 2 type story 的目录
r($tree->getOptionMenuTest($root[2], $type[0])) && p() && e('11'); // 测试获取root 41 type story 的目录
r($tree->getOptionMenuTest($root[3], $type[0])) && p() && e('11'); // 测试获取root 42 type story 的目录
r($tree->getOptionMenuTest($root[4], $type[1])) && p() && e('5');  // 测试获取root 101 type task 的目录
r($tree->getOptionMenuTest($root[5], $type[1])) && p() && e('4');  // 测试获取root 102 type task 的目录
r($tree->getOptionMenuTest($root[0], $type[2])) && p() && e('4');  // 测试获取root 1 type doc 的目录
r($tree->getOptionMenuTest($root[1], $type[2])) && p() && e('4');  // 测试获取root 2 type doc 的目录