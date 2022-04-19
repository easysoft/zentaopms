#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->getPairs();
cid=1
pid=1

测试获取 产品41 的分支信息对 >> ,0,1,2
测试获取 产品41 active 的分支信息对 >> ,0,1
测试获取 产品41 noempty 的分支信息对 >> ,1,2
测试获取 产品41 all 的分支信息对 >> ,all,0,1,2
测试获取 产品41 withClosed 的分支信息对 >> ,0,1,2
测试获取 产品41 active 执行141 的分支信息对 >> ,0
测试获取 产品41 active 执行141 合并分支1 4 的分支信息对 >> ,0
测试获取 产品41 的分支信息对 >> ,0,3,4
测试获取 产品41 active 的分支信息对 >> ,0,3
测试获取 产品41 noempty 的分支信息对 >> ,3,4
测试获取 产品41 all 的分支信息对 >> ,all,0,3,4
测试获取 产品41 withClosed 的分支信息对 >> ,0,3,4
测试获取 产品41 active 执行141 的分支信息对 >> 0
测试获取 产品41 active 执行141 合并分支1 4 的分支信息对 >> 0
测试获取 产品41 的分支信息对 >> ,0,5,6
测试获取 产品41 active 的分支信息对 >> ,0,5
测试获取 产品41 noempty 的分支信息对 >> ,5,6
测试获取 产品41 all 的分支信息对 >> ,all,0,5,6
测试获取 产品41 withClosed 的分支信息对 >> ,0,5,6
测试获取 产品41 active 执行141 的分支信息对 >> 0
测试获取 产品41 active 执行141 合并分支1 4 的分支信息对 >> 0

*/
$productID      = array(41, 42, 43);
$param          = array('active', 'noempty', 'all', 'withClosed');
$executionID    = array(141, 142, 143, null);
$mergedBranches = '1,4';

$branch = new branchTest();

r($branch->getPairsTest($productID[0]))                                              && p() && e(',0,1,2');     // 测试获取 产品41 的分支信息对
r($branch->getPairsTest($productID[0], $param[0]))                                   && p() && e(',0,1');       // 测试获取 产品41 active 的分支信息对
r($branch->getPairsTest($productID[0], $param[1]))                                   && p() && e(',1,2');       // 测试获取 产品41 noempty 的分支信息对
r($branch->getPairsTest($productID[0], $param[2]))                                   && p() && e(',all,0,1,2'); // 测试获取 产品41 all 的分支信息对
r($branch->getPairsTest($productID[0], $param[3]))                                   && p() && e(',0,1,2');     // 测试获取 产品41 withClosed 的分支信息对
r($branch->getPairsTest($productID[0], $param[0], $executionID[0]))                  && p() && e(',0');         // 测试获取 产品41 active 执行141 的分支信息对
r($branch->getPairsTest($productID[0], $param[0], $executionID[0], $mergedBranches)) && p() && e(',0');         // 测试获取 产品41 active 执行141 合并分支1 4 的分支信息对
r($branch->getPairsTest($productID[1]))                                              && p() && e(',0,3,4');     // 测试获取 产品41 的分支信息对
r($branch->getPairsTest($productID[1], $param[0]))                                   && p() && e(',0,3');       // 测试获取 产品41 active 的分支信息对
r($branch->getPairsTest($productID[1], $param[1]))                                   && p() && e(',3,4');       // 测试获取 产品41 noempty 的分支信息对
r($branch->getPairsTest($productID[1], $param[2]))                                   && p() && e(',all,0,3,4'); // 测试获取 产品41 all 的分支信息对
r($branch->getPairsTest($productID[1], $param[3]))                                   && p() && e(',0,3,4');     // 测试获取 产品41 withClosed 的分支信息对
r($branch->getPairsTest($productID[1], $param[0], $executionID[0]))                  && p() && e('0');          // 测试获取 产品41 active 执行141 的分支信息对
r($branch->getPairsTest($productID[1], $param[0], $executionID[0], $mergedBranches)) && p() && e('0');          // 测试获取 产品41 active 执行141 合并分支1 4 的分支信息对
r($branch->getPairsTest($productID[2]))                                              && p() && e(',0,5,6');     // 测试获取 产品41 的分支信息对
r($branch->getPairsTest($productID[2], $param[0]))                                   && p() && e(',0,5');       // 测试获取 产品41 active 的分支信息对
r($branch->getPairsTest($productID[2], $param[1]))                                   && p() && e(',5,6');       // 测试获取 产品41 noempty 的分支信息对
r($branch->getPairsTest($productID[2], $param[2]))                                   && p() && e(',all,0,5,6'); // 测试获取 产品41 all 的分支信息对
r($branch->getPairsTest($productID[2], $param[3]))                                   && p() && e(',0,5,6');     // 测试获取 产品41 withClosed 的分支信息对
r($branch->getPairsTest($productID[2], $param[0], $executionID[0]))                  && p() && e('0');          // 测试获取 产品41 active 执行141 的分支信息对
r($branch->getPairsTest($productID[2], $param[0], $executionID[0], $mergedBranches)) && p() && e('0');          // 测试获取 产品41 active 执行141 合并分支1 4 的分支信息对