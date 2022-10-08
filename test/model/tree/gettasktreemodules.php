#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getTaskTreeModules();
cid=1
pid=1

测试获取 execution 101 parent false story 的树 >> ,1822,1824,21,22,23,3021
测试获取 execution 102 parent false story 的树 >> ,1826,1828,24,25,26,3022
测试获取 execution 103 parent false story 的树 >> ,1830,1832,27,28,29,3023
测试获取 execution 104 parent false story 的树 >> ,1834,1836,30,31,32,3024
测试获取 execution 101 parent true story 的树 >> ,,1822,1824,21,22,23,3021
测试获取 execution 102 parent true story 的树 >> ,,1826,1828,24,25,26,23,3022
测试获取 execution 103 parent true story 的树 >> ,,1830,1832,27,28,29,25,3023
测试获取 execution 104 parent true story 的树 >> ,,1834,1836,30,31,32,27,3024
测试获取 execution 101 parent false case 的树 >> ,21,22,23,3021
测试获取 execution 102 parent false case 的树 >> ,24,25,26,3022
测试获取 execution 103 parent false case 的树 >> ,27,28,29,3023
测试获取 execution 104 parent false case 的树 >> ,30,31,32,3024
测试获取 execution 101 parent true case 的树 >> ,,21,22,23,3021
测试获取 execution 102 parent true case 的树 >> ,,24,25,26,23,3022
测试获取 execution 103 parent true case 的树 >> ,,27,28,29,25,3023
测试获取 execution 104 parent true case 的树 >> ,,30,31,32,27,3024

*/
$executionID = array(101, 102, 103, 104);
$parent      = array(false, true);
$linkObject  = array('story', 'case');

$tree = new treeTest();

r($tree->getTaskTreeModulesTest($executionID[0], $parent[0], $linkObject[0])) && p() && e(',1822,1824,21,22,23,3021');     // 测试获取 execution 101 parent false story 的树
r($tree->getTaskTreeModulesTest($executionID[1], $parent[0], $linkObject[0])) && p() && e(',1826,1828,24,25,26,3022');     // 测试获取 execution 102 parent false story 的树
r($tree->getTaskTreeModulesTest($executionID[2], $parent[0], $linkObject[0])) && p() && e(',1830,1832,27,28,29,3023');     // 测试获取 execution 103 parent false story 的树
r($tree->getTaskTreeModulesTest($executionID[3], $parent[0], $linkObject[0])) && p() && e(',1834,1836,30,31,32,3024');     // 测试获取 execution 104 parent false story 的树
r($tree->getTaskTreeModulesTest($executionID[0], $parent[1], $linkObject[0])) && p() && e(',,1822,1824,21,22,23,3021');    // 测试获取 execution 101 parent true story 的树
r($tree->getTaskTreeModulesTest($executionID[1], $parent[1], $linkObject[0])) && p() && e(',,1826,1828,24,25,26,23,3022'); // 测试获取 execution 102 parent true story 的树
r($tree->getTaskTreeModulesTest($executionID[2], $parent[1], $linkObject[0])) && p() && e(',,1830,1832,27,28,29,25,3023'); // 测试获取 execution 103 parent true story 的树
r($tree->getTaskTreeModulesTest($executionID[3], $parent[1], $linkObject[0])) && p() && e(',,1834,1836,30,31,32,27,3024'); // 测试获取 execution 104 parent true story 的树
r($tree->getTaskTreeModulesTest($executionID[0], $parent[0], $linkObject[1])) && p() && e(',21,22,23,3021');               // 测试获取 execution 101 parent false case 的树
r($tree->getTaskTreeModulesTest($executionID[1], $parent[0], $linkObject[1])) && p() && e(',24,25,26,3022');               // 测试获取 execution 102 parent false case 的树
r($tree->getTaskTreeModulesTest($executionID[2], $parent[0], $linkObject[1])) && p() && e(',27,28,29,3023');               // 测试获取 execution 103 parent false case 的树
r($tree->getTaskTreeModulesTest($executionID[3], $parent[0], $linkObject[1])) && p() && e(',30,31,32,3024');               // 测试获取 execution 104 parent false case 的树
r($tree->getTaskTreeModulesTest($executionID[0], $parent[1], $linkObject[1])) && p() && e(',,21,22,23,3021');              // 测试获取 execution 101 parent true case 的树
r($tree->getTaskTreeModulesTest($executionID[1], $parent[1], $linkObject[1])) && p() && e(',,24,25,26,23,3022');           // 测试获取 execution 102 parent true case 的树
r($tree->getTaskTreeModulesTest($executionID[2], $parent[1], $linkObject[1])) && p() && e(',,27,28,29,25,3023');           // 测试获取 execution 103 parent true case 的树
r($tree->getTaskTreeModulesTest($executionID[3], $parent[1], $linkObject[1])) && p() && e(',,30,31,32,27,3024');           // 测试获取 execution 104 parent true case 的树