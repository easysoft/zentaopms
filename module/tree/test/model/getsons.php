#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getSons();
timeout=0
cid=1

- 测试获取root 1 module 1 type task branch 0 的子module @,6

- 测试获取root 1 module 2 type story branch 0 的子module @,7

- 测试获取root 1 module 3 type doc branch 0 的子module @,8

- 测试获取root 1 module 4 type bug branch 0 的子module @,9

- 测试获取root 1 module 5 type case branch 0 的子module @,10

- 测试获取root 1 module 6 type task branch 0 的子module @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(20);

$root   = array(1, 2, 41);
$module = array(1, 2, 3, 4, 5, 6);
$type   = array('task', 'story', 'doc', 'bug', 'case');
$branch = array(0, 1);

$tree = new treeTest();

r($tree->getSonsTest($root[0], $module[0], $type[0], $branch[0])) && p() && e(',6');  // 测试获取root 1 module 1 type task branch 0 的子module
r($tree->getSonsTest($root[0], $module[1], $type[1], $branch[0])) && p() && e(',7');  // 测试获取root 1 module 2 type story branch 0 的子module
r($tree->getSonsTest($root[0], $module[2], $type[2], $branch[0])) && p() && e(',8');  // 测试获取root 1 module 3 type doc branch 0 的子module
r($tree->getSonsTest($root[0], $module[3], $type[3], $branch[0])) && p() && e(',9');  // 测试获取root 1 module 4 type bug branch 0 的子module
r($tree->getSonsTest($root[0], $module[4], $type[4], $branch[0])) && p() && e(',10'); // 测试获取root 1 module 5 type case branch 0 的子module
r($tree->getSonsTest($root[0], $module[5], $type[0], $branch[0])) && p() && e('0');   // 测试获取root 1 module 6 type task branch 0 的子module