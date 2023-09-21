#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/branch.class.php';

zdTable('product')->config('product')->gen(10);
zdTable('branch')->config('branch')->gen(20);
zdTable('project')->config('execution')->gen(20);
zdTable('projectproduct')->config('projectproduct')->gen(20);
su('admin');

/**

title=测试 branchModel->getList();
timeout=0
cid=1

*/
$productID   = array(6, 5, 11);
$executionID = array(101, 102, 103, 0);
$browseType  = array('all', 'closed', 'active');
$mainBranch  = false;

$branch = new branchTest();

r($branch->getListTest($productID[0]))                                               && p('', '|') && e(',0,1,17,2,16');
r($branch->getListTest($productID[0], $executionID[0]))                              && p('', '|') && e('0');
r($branch->getListTest($productID[0], $executionID[0], $browseType[0]))              && p('', '|') && e('0');
r($branch->getListTest($productID[0], $executionID[1], $browseType[0]))              && p('', '|') && e(',0,1');
r($branch->getListTest($productID[0], $executionID[1], $browseType[1]))              && p('', '|') && e('0');
r($branch->getListTest($productID[0], $executionID[1], $browseType[2]))              && p('', '|') && e(',0,1');
r($branch->getListTest($productID[0], $executionID[1], $browseType[0], $mainBranch)) && p('', '|') && e(',1');

r($branch->getListTest($productID[1]))                                               && p('', '|') && e(',0');
r($branch->getListTest($productID[1], $executionID[1]))                              && p('', '|') && e('0');
r($branch->getListTest($productID[1], $executionID[1], $browseType[0]))              && p('', '|') && e('0');
r($branch->getListTest($productID[1], $executionID[3], $browseType[0]))              && p('', '|') && e(',0');
r($branch->getListTest($productID[1], $executionID[3], $browseType[1]))              && p('', '|') && e('0');
r($branch->getListTest($productID[1], $executionID[3], $browseType[2]))              && p('', '|') && e(',0');
r($branch->getListTest($productID[1], $executionID[3], $browseType[0], $mainBranch)) && p('', '|') && e('0');

r($branch->getListTest($productID[2]))                                               && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[2]))                              && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[2], $browseType[0]))              && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[3], $browseType[0]))              && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[3], $browseType[1]))              && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[3], $browseType[2]))              && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[3], $browseType[0], $mainBranch)) && p('', '|') && e('0');
