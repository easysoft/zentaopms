#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getSons();
cid=1
pid=1

测试获取root 1 module 1821 type story branch 0 的子module >> ,2621,2622
测试获取root 2 module 1822 type story branch 0 的子module >> ,2623,2624
测试获取root 41 module 1981 type story branch 0 的子module >> ,2701,2702
测试获取root 41 module 1981 type story branch 1 的子module >> ,2701,2702
测试获取root 41 module 1981 type story branch 2 的子module >> ,2701,2702
测试获取root 41 module 1982 type story branch 0 的子module >> 0
测试获取root 41 module 1982 type story branch 1 的子module >> 0
测试获取root 41 module 1982 type story branch 2 的子module >> 0
测试获取root 42 module 1985 type story branch 0 的子module >> ,2703,2704
测试获取root 42 module 1985 type story branch 1 的子module >> ,2703,2704
测试获取root 42 module 1985 type story branch 2 的子module >> ,2703,2704
测试获取root 42 module 1987 type story branch 0 的子module >> 0
测试获取root 42 module 1987 type story branch 1 的子module >> 0
测试获取root 42 module 1987 type story branch 2 的子module >> 0
测试获取root 101 module 21 type task branch 0 的子module >> ,3021
测试获取root 102 module 22 type task branch 0 的子module >> 0
测试获取root 1 module 3621 type doc branch 0 的子module >> ,3721,3722
测试获取root 2 module 3621 type doc branch 0 的子module >> ,3723,3724

*/
$root   = array(1, 2, 41, 42, 101, 102);
$module = array(1821, 1825, 1981, 1982, 1985, 1987, 21, 24, 3621, 3622);
$type   = array('story', 'task', 'doc');
$branch = array(0, 1, 2);

$tree = new treeTest();

r($tree->getSonsTest($root[0], $module[0], $type[0], $branch[0])) && p() && e(',2621,2622'); // 测试获取root 1 module 1821 type story branch 0 的子module
r($tree->getSonsTest($root[1], $module[1], $type[0], $branch[0])) && p() && e(',2623,2624'); // 测试获取root 2 module 1822 type story branch 0 的子module
r($tree->getSonsTest($root[2], $module[2], $type[0], $branch[0])) && p() && e(',2701,2702'); // 测试获取root 41 module 1981 type story branch 0 的子module
r($tree->getSonsTest($root[2], $module[2], $type[0], $branch[1])) && p() && e(',2701,2702'); // 测试获取root 41 module 1981 type story branch 1 的子module
r($tree->getSonsTest($root[2], $module[2], $type[0], $branch[2])) && p() && e(',2701,2702'); // 测试获取root 41 module 1981 type story branch 2 的子module
r($tree->getSonsTest($root[2], $module[3], $type[0], $branch[0])) && p() && e('0');          // 测试获取root 41 module 1982 type story branch 0 的子module
r($tree->getSonsTest($root[2], $module[3], $type[0], $branch[1])) && p() && e('0');          // 测试获取root 41 module 1982 type story branch 1 的子module
r($tree->getSonsTest($root[2], $module[3], $type[0], $branch[2])) && p() && e('0');          // 测试获取root 41 module 1982 type story branch 2 的子module
r($tree->getSonsTest($root[3], $module[4], $type[0], $branch[0])) && p() && e(',2703,2704'); // 测试获取root 42 module 1985 type story branch 0 的子module
r($tree->getSonsTest($root[3], $module[4], $type[0], $branch[1])) && p() && e(',2703,2704'); // 测试获取root 42 module 1985 type story branch 1 的子module
r($tree->getSonsTest($root[3], $module[4], $type[0], $branch[2])) && p() && e(',2703,2704'); // 测试获取root 42 module 1985 type story branch 2 的子module
r($tree->getSonsTest($root[3], $module[5], $type[0], $branch[0])) && p() && e('0');          // 测试获取root 42 module 1987 type story branch 0 的子module
r($tree->getSonsTest($root[3], $module[5], $type[0], $branch[1])) && p() && e('0');          // 测试获取root 42 module 1987 type story branch 1 的子module
r($tree->getSonsTest($root[3], $module[5], $type[0], $branch[2])) && p() && e('0');          // 测试获取root 42 module 1987 type story branch 2 的子module
r($tree->getSonsTest($root[4], $module[6], $type[1], $branch[0])) && p() && e(',3021');      // 测试获取root 101 module 21 type task branch 0 的子module
r($tree->getSonsTest($root[5], $module[7], $type[1], $branch[0])) && p() && e('0');          // 测试获取root 102 module 22 type task branch 0 的子module
r($tree->getSonsTest($root[0], $module[8], $type[2], $branch[0])) && p() && e(',3721,3722'); // 测试获取root 1 module 3621 type doc branch 0 的子module
r($tree->getSonsTest($root[1], $module[9], $type[2], $branch[0])) && p() && e(',3723,3724'); // 测试获取root 2 module 3621 type doc branch 0 的子module