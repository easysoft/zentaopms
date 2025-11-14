#!/usr/bin/env php
<?php

/**

title=测试 productZen::getBranchID();
timeout=0
cid=17574

- 测试产品为空时返回 'all' @all
- 测试产品类型为 normal 时返回 'all' @all
- 测试产品类型为 branch,branch参数为空,cookie中无preBranch @0
- 测试产品类型为 branch,branch参数为空,cookie中preBranch='1'且该分支存在 @1
- 测试产品类型为 branch,branch参数为空,cookie中preBranch='999'但该分支不存在 @0
- 测试产品类型为 branch,branch参数为'2',忽略cookie @2
- 测试产品类型为 platform,branch参数为'3' @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);
zenData('branch')->loadYaml('branch', false, 2)->gen(10);

su('admin');

$productTest = new productZenTest();

r($productTest->getBranchIDTest(null, '')) && p() && e('all');                                              // 测试产品为空时返回 'all'
r($productTest->getBranchIDTest((object)array('id' => 1, 'type' => 'normal'), '')) && p() && e('all');      // 测试产品类型为 normal 时返回 'all'
r($productTest->getBranchIDTest((object)array('id' => 2, 'type' => 'branch'), '', '')) && p() && e('0');    // 测试产品类型为 branch,branch参数为空,cookie中无preBranch
r($productTest->getBranchIDTest((object)array('id' => 2, 'type' => 'branch'), '', '1')) && p() && e('1');   // 测试产品类型为 branch,branch参数为空,cookie中preBranch='1'且该分支存在
r($productTest->getBranchIDTest((object)array('id' => 2, 'type' => 'branch'), '', '999')) && p() && e('0'); // 测试产品类型为 branch,branch参数为空,cookie中preBranch='999'但该分支不存在
r($productTest->getBranchIDTest((object)array('id' => 2, 'type' => 'branch'), '2', '1')) && p() && e('2');  // 测试产品类型为 branch,branch参数为'2',忽略cookie
r($productTest->getBranchIDTest((object)array('id' => 3, 'type' => 'platform'), '3')) && p() && e('3');     // 测试产品类型为 platform,branch参数为'3'