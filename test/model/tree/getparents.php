#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getParents();
cid=1
pid=1

获取 module 1821 的父module >> ,1821
获取 module 2621 的父module >> ,1821,2621
获取 module 2622 的父module >> ,1821,2622
获取 module 21 的父module >> ,21
获取 module 3021 的父module >> ,21,3021
获取 module 3022 的父module >> ,23,3022
获取 module 3621 的父module >> ,3621
获取 module 3721 的父module >> ,3621,3721
获取 module 3722 的父module >> ,3621,3722
获取 module 0 的父module >> 0

*/

$moduleID = array(1821, 2621, 2622, 21, 3021, 3022, 3621, 3721, 3722, 0);

$tree = new treeTest();

r($tree->getParentsTest($moduleID[0])) && p() && e(',1821');      // 获取 module 1821 的父module
r($tree->getParentsTest($moduleID[1])) && p() && e(',1821,2621'); // 获取 module 2621 的父module
r($tree->getParentsTest($moduleID[2])) && p() && e(',1821,2622'); // 获取 module 2622 的父module
r($tree->getParentsTest($moduleID[3])) && p() && e(',21');        // 获取 module 21 的父module
r($tree->getParentsTest($moduleID[4])) && p() && e(',21,3021');   // 获取 module 3021 的父module
r($tree->getParentsTest($moduleID[5])) && p() && e(',23,3022');   // 获取 module 3022 的父module
r($tree->getParentsTest($moduleID[6])) && p() && e(',3621');      // 获取 module 3621 的父module
r($tree->getParentsTest($moduleID[7])) && p() && e(',3621,3721'); // 获取 module 3721 的父module
r($tree->getParentsTest($moduleID[8])) && p() && e(',3621,3722'); // 获取 module 3722 的父module
r($tree->getParentsTest($moduleID[9])) && p() && e('0');          // 获取 module 0 的父module