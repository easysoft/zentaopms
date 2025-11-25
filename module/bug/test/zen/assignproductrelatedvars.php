#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignProductRelatedVars();
timeout=0
cid=15427

- 步骤1:空数据
 - 属性branchProduct @0
 - 属性modulesCount @0
- 步骤2:正常产品类型无bugs
 - 属性branchProduct @0
 - 属性modulesCount @3
- 步骤3:分支产品类型
 - 属性branchProduct @1
 - 属性modulesCount @2
- 步骤4:混合产品类型
 - 属性branchProduct @1
 - 属性modulesCount @2
- 步骤5:多个分支产品
 - 属性branchProduct @1
 - 属性modulesCount @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('product_assignproductrelatedvars', false, 2)->gen(10);
zenData('bug')->loadYaml('bug_assignproductrelatedvars', false, 2)->gen(20);
zenData('branch')->loadYaml('branch_assignproductrelatedvars', false, 2)->gen(10);
zenData('module')->gen(20);
zenData('productplan')->gen(10);

su('admin');

$bugTest = new bugZenTest();

$emptyBugs = array();
$emptyProducts = array();

$normalProducts = array(
    (object)array('id' => 1, 'name' => '产品1', 'type' => 'normal'),
    (object)array('id' => 2, 'name' => '产品2', 'type' => 'normal'),
    (object)array('id' => 3, 'name' => '产品3', 'type' => 'normal')
);

$branchProducts = array(
    (object)array('id' => 6, 'name' => 'Product6', 'type' => 'branch'),
    (object)array('id' => 7, 'name' => 'Product7', 'type' => 'branch')
);

$mixedProducts = array(
    (object)array('id' => 1, 'name' => '产品1', 'type' => 'normal'),
    (object)array('id' => 6, 'name' => 'Product6', 'type' => 'branch')
);

$singleNormalProduct = array(
    (object)array('id' => 1, 'name' => '产品1', 'type' => 'normal')
);

$multipleBranchProducts = array(
    (object)array('id' => 6, 'name' => 'Product6', 'type' => 'branch'),
    (object)array('id' => 7, 'name' => 'Product7', 'type' => 'branch'),
    (object)array('id' => 8, 'name' => 'Product8', 'type' => 'platform')
);

r($bugTest->assignProductRelatedVarsTest($emptyBugs, $emptyProducts)) && p('branchProduct,modulesCount') && e('0,0'); // 步骤1:空数据
r($bugTest->assignProductRelatedVarsTest($emptyBugs, $normalProducts)) && p('branchProduct,modulesCount') && e('0,3'); // 步骤2:正常产品类型无bugs
r($bugTest->assignProductRelatedVarsTest($emptyBugs, $branchProducts)) && p('branchProduct,modulesCount') && e('1,2'); // 步骤3:分支产品类型
r($bugTest->assignProductRelatedVarsTest($emptyBugs, $mixedProducts)) && p('branchProduct,modulesCount') && e('1,2'); // 步骤4:混合产品类型
r($bugTest->assignProductRelatedVarsTest($emptyBugs, $multipleBranchProducts)) && p('branchProduct,modulesCount') && e('1,3'); // 步骤5:多个分支产品