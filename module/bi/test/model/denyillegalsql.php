#!/usr/bin/env php
<?php
global $app, $lang;

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$app->loadLang('bi');

/**

title=测试 biModel::denyIllegalSql();
timeout=0
cid=0

- 返回危险SQL拒绝文案 @1
- 拒绝请求时不输出业务数据 @0

*/

$testObj = new biModelTest();

r($testObj->denyIllegalSqlTest() === $lang->bi->errorIllegalSql) && p() && e(1); // 返回危险SQL拒绝文案
r(is_numeric($testObj->denyIllegalSqlTest()))                    && p() && e(0); // 拒绝请求时不输出业务数据
