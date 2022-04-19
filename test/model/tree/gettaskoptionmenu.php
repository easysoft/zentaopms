#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getTaskOptionMenu();
cid=1
pid=1

测试获取 root 1 task目录 >> ,/
测试获取 root 2 task目录 >> ,/
测试获取 root 3 task目录 >> ,/
测试获取 root 41 task目录 >> ,/,/已关闭的正常产品31/产品模块122,/已关闭的正常产品31/产品模块124
测试获取 root 42 task目录 >> ,/,/已关闭的正常产品32/产品模块126,/已关闭的正常产品32/产品模块128
测试获取 root 43 task目录 >> ,/,/已关闭的正常产品33/产品模块132,/已关闭的正常产品33/产品模块130
测试获取 root 101 task目录 >> ,/,/正常产品1/产品模块2,/正常产品1/产品模块4,/模块1,/模块1/执行子模块1,/模块2,/模块3
测试获取 root 101 product 1 task目录 >> ,/,/正常产品1/产品模块2,/正常产品1/产品模块4,/模块1,/模块1/执行子模块1,/模块2,/模块3
测试获取 root 101 product 1 startModule 3021 task目录 >> ,/,/正常产品1/产品模块2,/正常产品1/产品模块4,/模块1,/模块1/执行子模块1,/模块2,/模块3
测试获取 root 101 product 1 startModule 3021 allModule task目录 >> ,/,/正常产品1/产品模块2,/正常产品1/产品模块4,/模块1,/模块1/执行子模块1,/模块2,/模块3
测试获取 root 102 task目录 >> ,/,/正常产品2/产品模块6,/正常产品2/产品模块8,/模块4,/模块5,/模块6
测试获取 root 103 task目录 >> ,/,/正常产品3/产品模块12,/正常产品3/产品模块10,/模块7,/模块8,/模块9

*/
$root        = array(1, 2, 3, 41, 42, 43, 101, 102, 103);
$productID   = 1;
$startModule = 3021;
$extra       = 'allModule';

$tree = new treeTest();

r($tree->getTaskOptionMenuTest($root[0]))                                   && p() && e(',/');                                                                                   // 测试获取 root 1 task目录
r($tree->getTaskOptionMenuTest($root[1]))                                   && p() && e(',/');                                                                                   // 测试获取 root 2 task目录
r($tree->getTaskOptionMenuTest($root[2]))                                   && p() && e(',/');                                                                                   // 测试获取 root 3 task目录
r($tree->getTaskOptionMenuTest($root[3]))                                   && p() && e(',/,/已关闭的正常产品31/产品模块122,/已关闭的正常产品31/产品模块124');                   // 测试获取 root 41 task目录
r($tree->getTaskOptionMenuTest($root[4]))                                   && p() && e(',/,/已关闭的正常产品32/产品模块126,/已关闭的正常产品32/产品模块128');                   // 测试获取 root 42 task目录
r($tree->getTaskOptionMenuTest($root[5]))                                   && p() && e(',/,/已关闭的正常产品33/产品模块132,/已关闭的正常产品33/产品模块130');                   // 测试获取 root 43 task目录
r($tree->getTaskOptionMenuTest($root[6]))                                   && p() && e(',/,/正常产品1/产品模块2,/正常产品1/产品模块4,/模块1,/模块1/执行子模块1,/模块2,/模块3'); // 测试获取 root 101 task目录
r($tree->getTaskOptionMenuTest($root[6], $productID))                       && p() && e(',/,/正常产品1/产品模块2,/正常产品1/产品模块4,/模块1,/模块1/执行子模块1,/模块2,/模块3'); // 测试获取 root 101 product 1 task目录
r($tree->getTaskOptionMenuTest($root[6], $productID, $startModule))         && p() && e(',/,/正常产品1/产品模块2,/正常产品1/产品模块4,/模块1,/模块1/执行子模块1,/模块2,/模块3'); // 测试获取 root 101 product 1 startModule 3021 task目录
r($tree->getTaskOptionMenuTest($root[6], $productID, $startModule, $extra)) && p() && e(',/,/正常产品1/产品模块2,/正常产品1/产品模块4,/模块1,/模块1/执行子模块1,/模块2,/模块3'); // 测试获取 root 101 product 1 startModule 3021 allModule task目录
r($tree->getTaskOptionMenuTest($root[7]))                                   && p() && e(',/,/正常产品2/产品模块6,/正常产品2/产品模块8,/模块4,/模块5,/模块6');                    // 测试获取 root 102 task目录
r($tree->getTaskOptionMenuTest($root[8]))                                   && p() && e(',/,/正常产品3/产品模块12,/正常产品3/产品模块10,/模块7,/模块8,/模块9');                  // 测试获取 root 103 task目录