#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getAllChildId();
cid=1
pid=1

测试获取module 1821 的全部子项 >> ,1821,2621,2622 
测试获取module 1821 的全部子项 >> ,1822
测试获取module 1821 的全部子项 >> ,1825,2623,2624 
测试获取module 1821 的全部子项 >> ,1826
测试获取module 1821 的全部子项 >> ,1981,2701,2702 
测试获取module 1821 的全部子项 >> ,1982
测试获取module 1821 的全部子项 >> ,1985,2703,2704 
测试获取module 1821 的全部子项 >> ,1986
测试获取module 1821 的全部子项 >> ,21,3021
测试获取module 1821 的全部子项 >> ,22
测试获取module 1821 的全部子项 >> ,24
测试获取module 1821 的全部子项 >> ,25,3023

*/
$moduleID = array(1821, 1822, 1825, 1826, 1981, 1982, 1985, 1986, 21, 22, 24, 25);

$tree = new treeTest();

r($tree->getAllChildIdTest($moduleID[0]))  && p() && e(',1821,2621,2622 '); // 测试获取module 1821 的全部子项
r($tree->getAllChildIdTest($moduleID[1]))  && p() && e(',1822');            // 测试获取module 1821 的全部子项
r($tree->getAllChildIdTest($moduleID[2]))  && p() && e(',1825,2623,2624 '); // 测试获取module 1821 的全部子项
r($tree->getAllChildIdTest($moduleID[3]))  && p() && e(',1826');            // 测试获取module 1821 的全部子项
r($tree->getAllChildIdTest($moduleID[4]))  && p() && e(',1981,2701,2702 '); // 测试获取module 1821 的全部子项
r($tree->getAllChildIdTest($moduleID[5]))  && p() && e(',1982');            // 测试获取module 1821 的全部子项
r($tree->getAllChildIdTest($moduleID[6]))  && p() && e(',1985,2703,2704 '); // 测试获取module 1821 的全部子项
r($tree->getAllChildIdTest($moduleID[7]))  && p() && e(',1986');            // 测试获取module 1821 的全部子项
r($tree->getAllChildIdTest($moduleID[8]))  && p() && e(',21,3021');         // 测试获取module 1821 的全部子项
r($tree->getAllChildIdTest($moduleID[9]))  && p() && e(',22');              // 测试获取module 1821 的全部子项
r($tree->getAllChildIdTest($moduleID[10])) && p() && e(',24');              // 测试获取module 1821 的全部子项
r($tree->getAllChildIdTest($moduleID[11])) && p() && e(',25,3023');         // 测试获取module 1821 的全部子项