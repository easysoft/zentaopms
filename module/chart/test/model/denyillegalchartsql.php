#!/usr/bin/env php
<?php

global $app, $lang;

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
$app->loadLang('chart');

$testObj = new chartModelTest();
/**

title=测试 chartModel::denyIllegalChartSql();
timeout=0
cid=0

- 字段名非法返回字段文案 @1
- 操作符非法返回操作符文案 @1
- 过滤值非法返回过滤值文案 @1
- 聚合函数非法返回聚合函数文案 @1
- 日期分组非法返回日期文案 @1
- 未知类型返回过滤值文案 @1
- 拒绝请求时不输出业务数据 @0

*/

$messages = $lang->chart->errorIllegalSql;

r($testObj->denyIllegalChartSqlTest('field') === $messages['field'])       && p() && e(1); // 字段名非法返回字段文案
r($testObj->denyIllegalChartSqlTest('operator') === $messages['operator']) && p() && e(1); // 操作符非法返回操作符文案
r($testObj->denyIllegalChartSqlTest('value') === $messages['value'])       && p() && e(1); // 过滤值非法返回过滤值文案
r($testObj->denyIllegalChartSqlTest('agg') === $messages['agg'])           && p() && e(1); // 聚合函数非法返回聚合函数文案
r($testObj->denyIllegalChartSqlTest('date') === $messages['date'])         && p() && e(1); // 日期分组非法返回日期文案
r($testObj->denyIllegalChartSqlTest('unknown') === $messages['value'])     && p() && e(1); // 未知类型返回过滤值文案
r(is_numeric($testObj->denyIllegalChartSqlTest('value')))                  && p() && e(0); // 拒绝请求时不输出业务数据
