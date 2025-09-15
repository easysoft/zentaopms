#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
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
}

/**

title=测试 branchModel::manage();
timeout=0
cid=0

- 测试添加新分支到产品 @2
- 测试更新现有分支名称 @0
- 测试同时更新分支和添加新分支 @1
- 测试空新分支列表 @0
- 测试忽略空分支名称 @1

*/

global $tester;
$tester->loadModel('branch');

initData();

// 模拟POST数据：只添加新分支
$_POST = array('newbranch' => array('新分支1', '新分支2'), 'branch' => array());
r($tester->branch->manage(4)) && p() && e('2');                    // 测试添加新分支到产品

// 模拟POST数据：只更新现有分支
$_POST = array('branch' => array(1 => '更新的分支名'), 'newbranch' => array());
r($tester->branch->manage(1)) && p() && e('0');                    // 测试更新现有分支名称

// 模拟POST数据：同时更新和添加
$_POST = array('branch' => array(2 => '分支2更新'), 'newbranch' => array('混合新分支1'));
r($tester->branch->manage(2)) && p() && e('1');                    // 测试同时更新分支和添加新分支

// 模拟POST数据：空的新分支数组
$_POST = array('branch' => array(3 => '分支3更新'), 'newbranch' => array());
r($tester->branch->manage(3)) && p() && e('0');                    // 测试空新分支列表

// 模拟POST数据：包含空字符串的新分支（会被忽略）
$_POST = array('newbranch' => array('', '有效新分支'), 'branch' => array());
r($tester->branch->manage(5)) && p() && e('1');                    // 测试忽略空分支名称