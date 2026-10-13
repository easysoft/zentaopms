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
$app->loadLang('chart');

$testObj = new chartModelTest();

/**

title=测试 chartModel::checkChartSqlParams();
timeout=0
cid=0

- 合法日期分组字段与聚合函数通过校验 @1
- 空日期分组也通过校验 @1
- 合法过滤器通过校验 @1
- 非法日期分组被拒绝 @0
- 非法分组字段名被拒绝 @0
- 非法聚合函数被拒绝 @0
- 过滤器值注入 payload 被拒绝 @0
- 过滤器操作符非白名单被拒绝 @0

*/

$filters = array('name' => array('operator' => '=', 'value' => "'a'"));

r($testObj->checkChartSqlParamsTest('YEAR', 'name', 'total', 'count'))                       && p() && e(1); // 合法日期分组字段与聚合函数通过校验
r($testObj->checkChartSqlParamsTest('', 'name', 'total', 'sum'))                             && p() && e(1); // 空日期分组也通过校验
r($testObj->checkChartSqlParamsTest('MONTH', 'zt_user.name', 'total', 'distinct', $filters)) && p() && e(1); // 合法过滤器通过校验
r($testObj->checkChartSqlParamsTest('HOUR', 'name', 'total', 'count'))                       && p() && e(0); // 非法日期分组被拒绝
r($testObj->checkChartSqlParamsTest('YEAR', 'name`', 'total', 'count'))                      && p() && e(0); // 非法分组字段名被拒绝
r($testObj->checkChartSqlParamsTest('YEAR', 'name', 'total', 'median'))                      && p() && e(0); // 非法聚合函数被拒绝
r($testObj->checkChartSqlParamsTest('YEAR', 'name', 'total', 'count', array('name' => array('operator' => '=', 'value' => "'a' and sleep(3) and '1'='1'")))) && p() && e(0); // 过滤器值注入 payload 被拒绝
r($testObj->checkChartSqlParamsTest('YEAR', 'name', 'total', 'count', array('name' => array('operator' => '= 1 or 1=1', 'value' => "'a'")))) && p() && e(0); // 过滤器操作符非白名单被拒绝
