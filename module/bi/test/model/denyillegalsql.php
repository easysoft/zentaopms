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

- 拒绝请求时返回中文危险SQL提示 @SQL 语句包含危险语法（写文件/读文件），请求已被拒绝。
- 提示文案与bi语言包中的errorIllegalSql一致 @1
- 重复调用返回相同的拒绝文案 @SQL 语句包含危险语法（写文件/读文件），请求已被拒绝。
- 拒绝文案不是数字，不返回业务数据 @0
- 拒绝文案包含危险语法的说明 @1
- 拒绝文案以句号结尾 @1

*/

$testObj = new biModelTest();

$deniedMessage = $testObj->denyIllegalSqlTest();
r($deniedMessage) && p() && e('SQL 语句包含危险语法（写文件/读文件），请求已被拒绝。'); // 拒绝请求时返回中文危险SQL提示
r($deniedMessage === $lang->bi->errorIllegalSql) && p() && e('1'); // 提示文案与bi语言包中的errorIllegalSql一致
r($testObj->denyIllegalSqlTest()) && p() && e('SQL 语句包含危险语法（写文件/读文件），请求已被拒绝。'); // 重复调用返回相同的拒绝文案
r(is_numeric($deniedMessage)) && p() && e('0'); // 拒绝文案不是数字，不返回业务数据
r(strpos($deniedMessage, '危险语法') !== false) && p() && e('1'); // 拒绝文案包含危险语法的说明
r(substr($deniedMessage, -3) === '。') && p() && e('1'); // 拒绝文案以句号结尾
