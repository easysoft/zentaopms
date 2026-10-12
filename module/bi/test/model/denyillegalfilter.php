#!/usr/bin/env php
<?php
global $app, $lang;

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$app->loadLang('bi');

/**

title=测试 biModel::denyIllegalFilter();
timeout=0
cid=0

- 字段名非法返回字段文案 @1
- 操作符非法返回操作符文案 @1
- 过滤值非法返回过滤值文案 @1
- 未知类型返回过滤值文案 @1
- 拒绝请求时不输出业务数据 @0

*/

$testObj  = new biModelTest();
$messages = $lang->bi->errorIllegalFilter;

r($testObj->denyIllegalFilterTest('field') === $messages['field'])       && p() && e(1); // 字段名非法返回字段文案
r($testObj->denyIllegalFilterTest('operator') === $messages['operator']) && p() && e(1); // 操作符非法返回操作符文案
r($testObj->denyIllegalFilterTest('value') === $messages['value'])       && p() && e(1); // 过滤值非法返回过滤值文案
r($testObj->denyIllegalFilterTest('unknown') === $messages['value'])     && p() && e(1); // 未知类型返回过滤值文案
r(is_numeric($testObj->denyIllegalFilterTest('value')))                  && p() && e(0); // 拒绝请求时不输出业务数据
