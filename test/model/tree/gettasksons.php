#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getTaskSons();
cid=1
pid=1

测试获取 root 1 product 1 task模块的子模块 >> ,1821,2221,1822,2222,1823,2223,1824,2224
测试获取 root 1 product 2 task模块的子模块 >> ,1825,2225,1826,2226,1827,2227,1828,2228
测试获取 root 1 module 1821 task模块的子模块 >> ,2621,2622
测试获取 root 1 module 1825 task模块的子模块 >> 0
测试获取 root 1 module 21 task模块的子模块 >> 0
测试获取 root 1 module 23 task模块的子模块 >> 0
测试获取 root 2 product 1 task模块的子模块 >> ,1821,2221,1822,2222,1823,2223,1824,2224
测试获取 root 2 product 2 task模块的子模块 >> ,1825,2225,1826,2226,1827,2227,1828,2228
测试获取 root 2 module 1821 task模块的子模块 >> 0
测试获取 root 2 module 1825 task模块的子模块 >> ,2623,2624
测试获取 root 2 module 21 task模块的子模块 >> 0
测试获取 root 2 module 23 task模块的子模块 >> 0
测试获取 root 101 product 1 task模块的子模块 >> ,1821,2221,21,1822,2222,22,1823,2223,23,1824,2224 
测试获取 root 101 product 2 task模块的子模块 >> ,21,22,23,1825,2225,1826,2226,1827,2227,1828,2228 
测试获取 root 101 module 1821 task模块的子模块 >> 0
测试获取 root 101 module 1825 task模块的子模块 >> 0
测试获取 root 101 module 21 task模块的子模块 >> ,3021
测试获取 root 101 module 23 task模块的子模块 >> 0
测试获取 root 102 product 1 task模块的子模块 >> ,1821,2221,1822,2222,1823,2223,1824,2224,24,25,26 
测试获取 root 102 product 2 task模块的子模块 >> ,24,1825,2225,25,1826,2226,26,1827,2227,1828,2228 
测试获取 root 102 module 1821 task模块的子模块 >> 0
测试获取 root 102 module 1825 task模块的子模块 >> 0
测试获取 root 102 module 21 task模块的子模块 >> 0
测试获取 root 102 module 23 task模块的子模块 >> ,3022

*/
$rootID = array(1, 2, 101, 102);
$productID = array(0, 1, 2);
$moduleID  = array(0, 1821, 1825, 21, 23);

$tree = new treeTest();

r($tree->getTaskSonsTest($rootID[0], $productID[1], $moduleID[0])) && p() && e(',1821,2221,1822,2222,1823,2223,1824,2224');           // 测试获取 root 1 product 1 task模块的子模块
r($tree->getTaskSonsTest($rootID[0], $productID[2], $moduleID[0])) && p() && e(',1825,2225,1826,2226,1827,2227,1828,2228');           // 测试获取 root 1 product 2 task模块的子模块
r($tree->getTaskSonsTest($rootID[0], $productID[0], $moduleID[1])) && p() && e(',2621,2622');                                         // 测试获取 root 1 module 1821 task模块的子模块
r($tree->getTaskSonsTest($rootID[0], $productID[0], $moduleID[2])) && p() && e('0');                                                  // 测试获取 root 1 module 1825 task模块的子模块
r($tree->getTaskSonsTest($rootID[0], $productID[0], $moduleID[3])) && p() && e('0');                                                  // 测试获取 root 1 module 21 task模块的子模块
r($tree->getTaskSonsTest($rootID[0], $productID[0], $moduleID[4])) && p() && e('0');                                                  // 测试获取 root 1 module 23 task模块的子模块
r($tree->getTaskSonsTest($rootID[1], $productID[1], $moduleID[0])) && p() && e(',1821,2221,1822,2222,1823,2223,1824,2224');           // 测试获取 root 2 product 1 task模块的子模块
r($tree->getTaskSonsTest($rootID[1], $productID[2], $moduleID[0])) && p() && e(',1825,2225,1826,2226,1827,2227,1828,2228');           // 测试获取 root 2 product 2 task模块的子模块
r($tree->getTaskSonsTest($rootID[1], $productID[0], $moduleID[1])) && p() && e('0');                                                  // 测试获取 root 2 module 1821 task模块的子模块
r($tree->getTaskSonsTest($rootID[1], $productID[0], $moduleID[2])) && p() && e(',2623,2624');                                         // 测试获取 root 2 module 1825 task模块的子模块
r($tree->getTaskSonsTest($rootID[1], $productID[0], $moduleID[3])) && p() && e('0');                                                  // 测试获取 root 2 module 21 task模块的子模块
r($tree->getTaskSonsTest($rootID[1], $productID[0], $moduleID[4])) && p() && e('0');                                                  // 测试获取 root 2 module 23 task模块的子模块
r($tree->getTaskSonsTest($rootID[2], $productID[1], $moduleID[0])) && p() && e(',1821,2221,21,1822,2222,22,1823,2223,23,1824,2224 '); // 测试获取 root 101 product 1 task模块的子模块
r($tree->getTaskSonsTest($rootID[2], $productID[2], $moduleID[0])) && p() && e(',21,22,23,1825,2225,1826,2226,1827,2227,1828,2228 '); // 测试获取 root 101 product 2 task模块的子模块
r($tree->getTaskSonsTest($rootID[2], $productID[0], $moduleID[1])) && p() && e('0');                                                  // 测试获取 root 101 module 1821 task模块的子模块
r($tree->getTaskSonsTest($rootID[2], $productID[0], $moduleID[2])) && p() && e('0');                                                  // 测试获取 root 101 module 1825 task模块的子模块
r($tree->getTaskSonsTest($rootID[2], $productID[0], $moduleID[3])) && p() && e(',3021');                                              // 测试获取 root 101 module 21 task模块的子模块
r($tree->getTaskSonsTest($rootID[2], $productID[0], $moduleID[4])) && p() && e('0');                                                  // 测试获取 root 101 module 23 task模块的子模块
r($tree->getTaskSonsTest($rootID[3], $productID[1], $moduleID[0])) && p() && e(',1821,2221,1822,2222,1823,2223,1824,2224,24,25,26 '); // 测试获取 root 102 product 1 task模块的子模块
r($tree->getTaskSonsTest($rootID[3], $productID[2], $moduleID[0])) && p() && e(',24,1825,2225,25,1826,2226,26,1827,2227,1828,2228 '); // 测试获取 root 102 product 2 task模块的子模块
r($tree->getTaskSonsTest($rootID[3], $productID[0], $moduleID[1])) && p() && e('0');                                                  // 测试获取 root 102 module 1821 task模块的子模块
r($tree->getTaskSonsTest($rootID[3], $productID[0], $moduleID[2])) && p() && e('0');                                                  // 测试获取 root 102 module 1825 task模块的子模块
r($tree->getTaskSonsTest($rootID[3], $productID[0], $moduleID[3])) && p() && e('0');                                                  // 测试获取 root 102 module 21 task模块的子模块
r($tree->getTaskSonsTest($rootID[3], $productID[0], $moduleID[4])) && p() && e(',3022');                                              // 测试获取 root 102 module 23 task模块的子模块