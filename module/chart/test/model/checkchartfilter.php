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

title=测试 chartModel::checkChartFilter();
timeout=0
cid=0

- 合法等于过滤通过校验 @1
- 合法 IN 字面量列表通过校验 @1
- 合法 IS NULL 过滤通过校验 @1
- BETWEEN 字面量通过校验 @1
- 过滤值注入 payload 被拒绝 @0
- 报错注入 payload 被拒绝 @0
- 过滤操作符非白名单被拒绝 @0
- 过滤字段名非法被拒绝 @0
- 过滤字段名为空被拒绝 @0

*/

r($testObj->checkChartFilterTest('name', '=', "'a'"))                        && p() && e(1); // 合法等于过滤通过校验
r($testObj->checkChartFilterTest('name', 'IN', "('a', 'b')"))                && p() && e(1); // 合法 IN 字面量列表通过校验
r($testObj->checkChartFilterTest('name', 'IS NULL', ''))                     && p() && e(1); // 合法 IS NULL 过滤通过校验
r($testObj->checkChartFilterTest('createdDate', 'BETWEEN', "'2024-01-01' and '2024-01-02'")) && p() && e(1); // BETWEEN 字面量通过校验
r($testObj->checkChartFilterTest('name', '=', "'a' and sleep(3) and '1'='1'")) && p() && e(0); // 过滤值注入 payload 被拒绝
r($testObj->checkChartFilterTest('name', '=', "'a' and extractvalue(1,concat(0x7e,(select password from zt_user limit 1)))")) && p() && e(0); // 报错注入 payload 被拒绝
r($testObj->checkChartFilterTest('name', '= 1 or 1=1', "'a'"))              && p() && e(0); // 过滤操作符非白名单被拒绝
r($testObj->checkChartFilterTest('name`', '=', "'a'"))                     && p() && e(0); // 过滤字段名非法被拒绝
r($testObj->checkChartFilterTest('', '=', "'a'"))                          && p() && e(0); // 过滤字段名为空被拒绝
