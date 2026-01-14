#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=productpanModel->create();
timeout=0
cid=17627

- 测试正常创建属性title @测试创建1
- 测试正常创建属性title @测试创建1
- 测试不填名称创建失败第title条的0属性 @『计划名称』不能为空。
- 测试不填开始时间创建失败属性begin @『开始日期』不能为空。
- 测试不填结束日期创建失败属性end @『结束日期』不能为空。
- 测试不填开始日期和结束日期创建失败属性begin @『开始日期』不能为空。
- 测试没有uid属性title @测试创建1
- 测试创建子计划属性parent @1

*/

zenData('productplan')->gen(0);

$postData = new stdclass();
$postData->title   = '测试创建1';
$postData->begin   = '2021-10-25';
$postData->end     = '2021-10-29';
$postData->uid     = '623927843dd9b';
$postData->product = '2';
$postData->parent  = '0';

$noTitle = clone $postData;
$noTitle->title  = '';

$noBegin = clone $postData;
$noBegin->begin = '';

$noEnd =  clone$postData;
$noEnd->end  = '';

$noBeginEnd = clone $noBegin;
$noBeginEnd->end = '';

$noUid = clone $postData;
$noUid->uid = '';

$parent = clone $postData;
$parent->parent = 1;

$isFutureList = array(0, 1);

$planTester = new productPlan('admin');
r($planTester->createTest($postData,   $isFutureList[0])) && p('title')   && e('测试创建1');              // 测试正常创建
r($planTester->createTest($postData,   $isFutureList[1])) && p('title')   && e('测试创建1');              // 测试正常创建
r($planTester->createTest($noTitle,    $isFutureList[0])) && p('title:0') && e('『计划名称』不能为空。'); // 测试不填名称创建失败
r($planTester->createTest($noBegin,    $isFutureList[0])) && p('begin')   && e('『开始日期』不能为空。'); // 测试不填开始时间创建失败
r($planTester->createTest($noEnd,      $isFutureList[0])) && p('end')     && e('『结束日期』不能为空。'); // 测试不填结束日期创建失败
r($planTester->createTest($noBeginEnd, $isFutureList[0])) && p('begin')   && e('『开始日期』不能为空。'); // 测试不填开始日期和结束日期创建失败
r($planTester->createTest($noUid,      $isFutureList[0])) && p('title')   && e('测试创建1');              // 测试没有uid
r($planTester->createTest($parent,     $isFutureList[0])) && p('parent')  && e('1');                      // 测试创建子计划
