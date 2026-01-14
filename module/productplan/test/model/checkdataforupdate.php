#!/usr/bin/env php
<?php
/**

title=productpanModel->checkDataForUpdate();
timeout=0
cid=17624

- 测试正常数据 @1
- 测试不填写分支 @1
- 测试正常数据 @1
- 测试不填写分支属性branch[] @『所属分支』不能为空。
- 测试填写错误分支属性branch[] @分支『主干』被子计划关联，无法修改。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('product')->loadYaml('product')->gen(10);
$plan = zenData('productplan')->loadYaml('productplan');
$plan->product->range('1,6{4}');
$plan->parent->range('0,`-1`,2{3}');
$plan->gen(5);

$planIdList = array(1, 2);

$postData = new stdclass();
$postData->title   = '测试修改';
$postData->status  = 'doing';
$postData->begin   = '2021-03-01';
$postData->end     = '2021-06-15';
$postData->product = 6;
$postData->parent  = 0;
$postData->branch  = 0;

$emptyBranch = clone $postData;
$emptyBranch->branch = '';

$errorBranch = clone $postData;
$errorBranch->branch = 1;

$emptyTitle = clone $postData;
$emptyTitle->title = '';

$planTester = new productPlan('admin');
r($planTester->checkDataForUpdateTest($planIdList[0], $postData))    && p()           && e('1');                                    // 测试正常数据
r($planTester->checkDataForUpdateTest($planIdList[0], $emptyBranch)) && p()           && e('1');                                    // 测试不填写分支
r($planTester->checkDataForUpdateTest($planIdList[1], $postData))    && p()           && e('1');                                    // 测试正常数据
r($planTester->checkDataForUpdateTest($planIdList[1], $emptyBranch)) && p('branch[]') && e('『所属分支』不能为空。');               // 测试不填写分支
r($planTester->checkDataForUpdateTest($planIdList[1], $errorBranch)) && p('branch[]') && e('分支『主干』被子计划关联，无法修改。'); // 测试填写错误分支
