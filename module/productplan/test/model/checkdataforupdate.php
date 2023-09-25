#!/usr/bin/env php
<?php
/**

title=productpanModel->checkDataForUpdate();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('user')->gen(5);
zdTable('product')->config('product')->gen(10);
$plan = zdTable('productplan')->config('productplan');
$plan->product->range('6');
$plan->gen(5);

$planID = 1;

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

$planTester = new productPlan('admin');
r($planTester->checkDataForUpdateTest($planID, $postData))    && p()           && e('1');                                    // 测试正常数据
r($planTester->checkDataForUpdateTest($planID, $emptyBranch)) && p('branch[]') && e('『所属分支』不能为空。');               // 测试不填写分支
r($planTester->checkDataForUpdateTest($planID, $errorBranch)) && p('branch[]') && e('分支『主干』被子计划关联，无法修改。'); // 测试填写错误分支
