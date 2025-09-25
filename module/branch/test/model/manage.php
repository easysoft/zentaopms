#!/usr/bin/env php
<?php

/**

title=测试 branchModel::manage();
timeout=0
cid=0

- 测试添加两个新分支到产品 >> 期望返回2
- 测试只更新现有分支名称不添加新分支 >> 期望返回0
- 测试同时更新分支名称和添加新分支 >> 期望返回1
- 测试传入空的新分支数组 >> 期望返回0
- 测试忽略空字符串新分支名称 >> 期望返回1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

// zendata数据准备
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->type->range('normal{3},branch{4},platform{3}');
$product->status->range('normal{8},closed{2}');
$product->acl->range('open{6},private{4}');
$product->gen(10);

$branch = zenData('branch');
$branch->id->range('1-20');
$branch->product->range('1-10');
$branch->name->range('分支1,分支2,分支3,分支4,分支5,feature-1,feature-2,hotfix-1,hotfix-2,develop-1,develop-2,release-1,release-2,test-1,test-2,bugfix-1,bugfix-2,patch-1,patch-2,update-1');
$branch->status->range('active{15},closed{5}');
$branch->desc->range('测试分支描述{20}');
$branch->order->range('1-20');
$branch->gen(20);

// 用户登录
su('admin');

// 创建测试实例
$branchTest = new branchTest();

r($branchTest->manageTest(4, array(), array('新分支1', '新分支2'))) && p() && e('2');
r($branchTest->manageTest(1, array(1 => '更新的分支名'), array())) && p() && e('0');
r($branchTest->manageTest(2, array(2 => '分支2更新'), array('混合新分支1'))) && p() && e('1');
r($branchTest->manageTest(3, array(3 => '分支3更新'), array())) && p() && e('0');
r($branchTest->manageTest(5, array(), array('', '有效新分支'))) && p() && e('1');