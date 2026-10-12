#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('1-1');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('chart');

$testObj = new chartModelTest();
/**

title=测试 chartModel::isSafeWaterpoloConditionValue();
timeout=0
cid=0

- 中文文本通过校验 @1
- 数字通过校验 @1
- 日期文本通过校验 @1
- 空字符串通过校验 @1
- 含单引号不通过校验 @0
- 含双引号通过校验（值由单引号包裹，双引号无害） @1
- 含反斜线不通过校验 @0
- 含换行不通过校验 @0
- 拼接 payload 不通过校验 @0

*/

r($testObj->isSafeWaterpoloConditionValueTest('已完成'))            && p() && e(1); // 中文文本通过校验
r($testObj->isSafeWaterpoloConditionValueTest('123'))               && p() && e(1); // 数字通过校验
r($testObj->isSafeWaterpoloConditionValueTest('2024-01-01'))        && p() && e(1); // 日期文本通过校验
r($testObj->isSafeWaterpoloConditionValueTest(''))                  && p() && e(1); // 空字符串通过校验
r($testObj->isSafeWaterpoloConditionValueTest("a'b"))               && p() && e(0); // 含单引号不通过校验
r($testObj->isSafeWaterpoloConditionValueTest('a"b'))               && p() && e(1); // 含双引号通过校验（值由单引号包裹，双引号无害）
r($testObj->isSafeWaterpoloConditionValueTest('a\\b'))              && p() && e(0); // 含反斜线不通过校验
r($testObj->isSafeWaterpoloConditionValueTest("a\nb"))              && p() && e(0); // 含换行不通过校验
r($testObj->isSafeWaterpoloConditionValueTest("a' or '1'='1"))      && p() && e(0); // 拼接 payload 不通过校验
