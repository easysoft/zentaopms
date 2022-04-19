#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->getList();
cid=1
pid=1

测试获取产品 41的分支信息 >> ,0,1
测试获取产品 41 执行 141的分支信息 >> ,0
测试获取产品 41 执行 141 all 没有主干 的分支信息 >> ,0
测试获取产品 41 all 没有主干 的分支信息 >> ,0,1,2
测试获取产品 41 执行 141 closed 没有主干 的分支信息 >> ,2
测试获取产品 41 执行 141 active 没有主干 的分支信息 >> ,0,1
测试获取产品 41 执行 141 all 没有主干 的分支信息 >> ,1,2
测试获取产品 42的分支信息 >> ,0,3
测试获取产品 42 执行 142的分支信息 >> ,0
测试获取产品 42 执行 142 all 没有主干 的分支信息 >> ,0
测试获取产品 42 all 没有主干 的分支信息 >> ,0,3,4
测试获取产品 42 执行 142 closed 没有主干 的分支信息 >> ,4
测试获取产品 42 执行 142 active 没有主干 的分支信息 >> ,0,3
测试获取产品 42 执行 142 all 没有主干 的分支信息 >> ,3,4
测试获取产品 43的分支信息 >> ,0,5
测试获取产品 43 执行 143的分支信息 >> ,0
测试获取产品 43 执行 143 all 没有主干 的分支信息 >> ,0
测试获取产品 43 all 没有主干 的分支信息 >> ,0,5,6
测试获取产品 43 执行 143 closed 没有主干 的分支信息 >> ,6
测试获取产品 43 执行 143 active 没有主干 的分支信息 >> ,0,5
测试获取产品 43 执行 143 all 没有主干 的分支信息 >> ,5,6

*/
$productID   = array(41, 42, 43);
$executionID = array(141, 142, 143, null);
$browseType  = array('all', 'closed', 'active');
$mainBranch  = false;

$branch = new branchTest();

r($branch->getListTest($productID[0]))                                               && p() && e(',0,1');   // 测试获取产品 41的分支信息
r($branch->getListTest($productID[0], $executionID[0]))                              && p() && e(',0');     // 测试获取产品 41 执行 141的分支信息
r($branch->getListTest($productID[0], $executionID[0], $browseType[0]))              && p() && e(',0');     // 测试获取产品 41 执行 141 all 没有主干 的分支信息
r($branch->getListTest($productID[0], $executionID[3], $browseType[0]))              && p() && e(',0,1,2'); // 测试获取产品 41 all 没有主干 的分支信息
r($branch->getListTest($productID[0], $executionID[3], $browseType[1]))              && p() && e(',2');     // 测试获取产品 41 执行 141 closed 没有主干 的分支信息
r($branch->getListTest($productID[0], $executionID[3], $browseType[2]))              && p() && e(',0,1');   // 测试获取产品 41 执行 141 active 没有主干 的分支信息
r($branch->getListTest($productID[0], $executionID[3], $browseType[0], $mainBranch)) && p() && e(',1,2');   // 测试获取产品 41 执行 141 all 没有主干 的分支信息
r($branch->getListTest($productID[1]))                                               && p() && e(',0,3');   // 测试获取产品 42的分支信息
r($branch->getListTest($productID[1], $executionID[1]))                              && p() && e(',0');     // 测试获取产品 42 执行 142的分支信息
r($branch->getListTest($productID[1], $executionID[1], $browseType[0]))              && p() && e(',0');     // 测试获取产品 42 执行 142 all 没有主干 的分支信息
r($branch->getListTest($productID[1], $executionID[3], $browseType[0]))              && p() && e(',0,3,4'); // 测试获取产品 42 all 没有主干 的分支信息
r($branch->getListTest($productID[1], $executionID[3], $browseType[1]))              && p() && e(',4');     // 测试获取产品 42 执行 142 closed 没有主干 的分支信息
r($branch->getListTest($productID[1], $executionID[3], $browseType[2]))              && p() && e(',0,3');   // 测试获取产品 42 执行 142 active 没有主干 的分支信息
r($branch->getListTest($productID[1], $executionID[3], $browseType[0], $mainBranch)) && p() && e(',3,4');   // 测试获取产品 42 执行 142 all 没有主干 的分支信息
r($branch->getListTest($productID[2]))                                               && p() && e(',0,5');   // 测试获取产品 43的分支信息
r($branch->getListTest($productID[2], $executionID[2]))                              && p() && e(',0');     // 测试获取产品 43 执行 143的分支信息
r($branch->getListTest($productID[2], $executionID[2], $browseType[0]))              && p() && e(',0');     // 测试获取产品 43 执行 143 all 没有主干 的分支信息
r($branch->getListTest($productID[2], $executionID[3], $browseType[0]))              && p() && e(',0,5,6'); // 测试获取产品 43 all 没有主干 的分支信息
r($branch->getListTest($productID[2], $executionID[3], $browseType[1]))              && p() && e(',6');     // 测试获取产品 43 执行 143 closed 没有主干 的分支信息
r($branch->getListTest($productID[2], $executionID[3], $browseType[2]))              && p() && e(',0,5');   // 测试获取产品 43 执行 143 active 没有主干 的分支信息
r($branch->getListTest($productID[2], $executionID[3], $browseType[0], $mainBranch)) && p() && e(',5,6');   // 测试获取产品 43 执行 143 all 没有主干 的分支信息