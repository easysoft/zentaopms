#!/usr/bin/env php
<?php

/**

title=测试 projectZen::getOtherProducts();
timeout=0
cid=17945

- 步骤1:测试正常产品列表(无分支)
 - 属性2 @Product2
 - 属性3 @Product3
- 步骤2:测试有分支的产品
 - 属性1_10 @Product1_Branch10
 - 属性1_20 @Product1_Branch20
- 步骤3:测试已关联产品被过滤 @0
- 步骤4:测试已关联分支被过滤 @0
- 步骤5:测试空产品列表 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$projectTest = new projectZenTest();

// 4. 测试步骤

// 步骤1:测试正常产品列表(无分支),返回2个未关联的产品
$programProducts = array(1 => 'Product1', 2 => 'Product2', 3 => 'Product3');
$branchGroups = array();
$linkedBranches = array();
$linkedProducts = array(1 => (object)array('id' => 1, 'name' => 'Product1'));
r($projectTest->getOtherProductsTest($programProducts, $branchGroups, $linkedBranches, $linkedProducts)) && p('2,3') && e('Product2,Product3'); // 步骤1:测试正常产品列表(无分支)

// 步骤2:测试有分支的产品,返回产品_分支ID格式
$programProducts = array(1 => 'Product1');
$branchGroups = array(1 => array(10 => 'Branch10', 20 => 'Branch20'));
$linkedBranches = array();
$linkedProducts = array();
r($projectTest->getOtherProductsTest($programProducts, $branchGroups, $linkedBranches, $linkedProducts)) && p('1_10,1_20') && e('Product1_Branch10,Product1_Branch20'); // 步骤2:测试有分支的产品

// 步骤3:测试已关联产品被过滤,已关联的产品不在结果中
$programProducts = array(1 => 'Product1', 2 => 'Product2');
$branchGroups = array();
$linkedBranches = array();
$linkedProducts = array(1 => (object)array('id' => 1, 'name' => 'Product1'));
r(isset($projectTest->getOtherProductsTest($programProducts, $branchGroups, $linkedBranches, $linkedProducts)[1])) && p() && e('0'); // 步骤3:测试已关联产品被过滤

// 步骤4:测试已关联分支被过滤,已关联的分支不在结果中
$programProducts = array(1 => 'Product1');
$branchGroups = array(1 => array(10 => 'Branch10', 20 => 'Branch20'));
$linkedBranches = array(1 => array(10 => 10));
$linkedProducts = array(1 => (object)array('id' => 1, 'name' => 'Product1'));
r(isset($projectTest->getOtherProductsTest($programProducts, $branchGroups, $linkedBranches, $linkedProducts)['1_10'])) && p() && e('0'); // 步骤4:测试已关联分支被过滤

// 步骤5:测试空产品列表,返回空数组
$programProducts = array();
$branchGroups = array();
$linkedBranches = array();
$linkedProducts = array();
r(count($projectTest->getOtherProductsTest($programProducts, $branchGroups, $linkedBranches, $linkedProducts))) && p() && e('0'); // 步骤5:测试空产品列表