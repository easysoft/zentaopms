#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getLinkBranches();
timeout=0
cid=18140

- 测试步骤1:空产品数组 @0
- 测试步骤2:仅包含normal类型产品 @0
- 测试步骤3:包含branch类型产品但无分支 @0
- 测试步骤4:验证branch类型产品返回0个分支(无分支数据) @0
- 测试步骤5:包含多个产品类型,返回0个分支(无分支数据) @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zendata('product')->gen(0);
zendata('branch')->gen(0);

su('admin');

$repoTest = new repoZenTest();

$emptyProducts = array();
$normalProducts = array();
$product1 = new stdClass();
$product1->id = 1;
$product1->name = 'Normal Product';
$product1->type = 'normal';
$normalProducts[1] = $product1;

$branchProducts = array();
$product2 = new stdClass();
$product2->id = 2;
$product2->name = 'Branch Product';
$product2->type = 'branch';
$branchProducts[2] = $product2;

$platformProducts = array();
$product3 = new stdClass();
$product3->id = 3;
$product3->name = 'Platform Product';
$product3->type = 'platform';
$platformProducts[3] = $product3;

$mixedProducts = array();
$product4 = new stdClass();
$product4->id = 1;
$product4->name = 'Normal Product 1';
$product4->type = 'normal';
$mixedProducts[1] = $product4;

$product5 = new stdClass();
$product5->id = 2;
$product5->name = 'Branch Product 1';
$product5->type = 'branch';
$mixedProducts[2] = $product5;

$product6 = new stdClass();
$product6->id = 3;
$product6->name = 'Platform Product 1';
$product6->type = 'platform';
$mixedProducts[3] = $product6;

r($repoTest->getLinkBranchesTest($emptyProducts)) && p() && e('0'); // 测试步骤1:空产品数组
r($repoTest->getLinkBranchesTest($normalProducts)) && p() && e('0'); // 测试步骤2:仅包含normal类型产品
r($repoTest->getLinkBranchesTest($branchProducts)) && p() && e('0'); // 测试步骤3:包含branch类型产品但无分支
r(count($repoTest->getLinkBranchesTest($branchProducts))) && p() && e('0'); // 测试步骤4:验证branch类型产品返回0个分支(无分支数据)
r(count($repoTest->getLinkBranchesTest($mixedProducts))) && p() && e('0'); // 测试步骤5:包含多个产品类型,返回0个分支(无分支数据)