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

title=测试 chartModel::checkChartSqlParams();
timeout=0
cid=0

- 无日期分组无过滤器通过校验 @1
- 合法日期分组通过校验 @1
- 合法过滤器通过校验 @1
- 合法 BETWEEN 过滤器通过校验 @1
- 日期分组注入被拒绝 @0
- 聚合函数非白名单被拒绝 @0
- 分组字段名非法被拒绝 @0
- 指标字段名非法被拒绝 @0
- 指标字段名为空被拒绝 @0
- 过滤器值注入被拒绝 @0

*/

$literalFilters = array('name' => array('operator' => '=', 'value' => "'a'"));
$betweenFilters = array('createdDate' => array('operator' => 'BETWEEN', 'value' => "'2024-01-01' and '2024-01-02'"));
$injectFilters  = array('name' => array('operator' => '=', 'value' => "'a' and sleep(3) and '1'='1'"));

r($testObj->checkChartSqlParamsTest('', 'name', 'id', 'count', array()))                        && p() && e(1); // 无日期分组无过滤器通过校验
r($testObj->checkChartSqlParamsTest('MONTH', 'name', 'id', 'count', array()))                   && p() && e(1); // 合法日期分组通过校验
r($testObj->checkChartSqlParamsTest('', 'name', 'id', 'count', $literalFilters))                && p() && e(1); // 合法过滤器通过校验
r($testObj->checkChartSqlParamsTest('', 'createdDate', 'id', 'count', $betweenFilters))         && p() && e(1); // 合法 BETWEEN 过滤器通过校验
r($testObj->checkChartSqlParamsTest('MONTH) or sleep(3)--', 'name', 'id', 'count', array()))    && p() && e(0); // 日期分组注入被拒绝
r($testObj->checkChartSqlParamsTest('', 'name', 'id', 'count) from zt_user--', array()))        && p() && e(0); // 聚合函数非白名单被拒绝
r($testObj->checkChartSqlParamsTest('', 'name`', 'id', 'count', array()))                       && p() && e(0); // 分组字段名非法被拒绝
r($testObj->checkChartSqlParamsTest('', 'name', 'id`', 'count', array()))                       && p() && e(0); // 指标字段名非法被拒绝
r($testObj->checkChartSqlParamsTest('', 'name', '', 'count', array()))                          && p() && e(0); // 指标字段名为空被拒绝
r($testObj->checkChartSqlParamsTest('', 'name', 'id', 'count', $injectFilters))                 && p() && e(0); // 过滤器值注入被拒绝
