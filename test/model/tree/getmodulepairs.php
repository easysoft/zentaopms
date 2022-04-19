#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getModulePairs();
cid=1
pid=1

测试获取root 1 type story 的树结构 >> 12
测试获取root 2 type story 的树结构 >> 12
测试获取root 3 type story 的树结构 >> 12
测试获取root 41 type story 的树结构 >> 12
测试获取root 42 type story 的树结构 >> 12
测试获取root 43 type story 的树结构 >> 12
测试获取root 101 type task 的树结构 >> 40
测试获取root 102 type task 的树结构 >> 40
测试获取root 103 type task 的树结构 >> 40

*/
$root = array(1, 2, 3, 41, 42, 43, 101, 102, 103);
$type = array('story', 'task');

$tree = new treeTest();

r($tree->getModulePairsTest($root[0], $type[0])) && p() && e('12'); // 测试获取root 1 type story 的树结构
r($tree->getModulePairsTest($root[1], $type[0])) && p() && e('12'); // 测试获取root 2 type story 的树结构
r($tree->getModulePairsTest($root[2], $type[0])) && p() && e('12'); // 测试获取root 3 type story 的树结构
r($tree->getModulePairsTest($root[3], $type[0])) && p() && e('12'); // 测试获取root 41 type story 的树结构
r($tree->getModulePairsTest($root[4], $type[0])) && p() && e('12'); // 测试获取root 42 type story 的树结构
r($tree->getModulePairsTest($root[5], $type[0])) && p() && e('12'); // 测试获取root 43 type story 的树结构
r($tree->getModulePairsTest($root[6], $type[1])) && p() && e('40'); // 测试获取root 101 type task 的树结构
r($tree->getModulePairsTest($root[7], $type[1])) && p() && e('40'); // 测试获取root 102 type task 的树结构
r($tree->getModulePairsTest($root[8], $type[1])) && p() && e('40'); // 测试获取root 103 type task 的树结构