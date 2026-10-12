#!/usr/bin/env php
<?php

global $app;

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

/**

title=测试 chartModel::checkClientSql();
timeout=0
cid=0

- 正常查询用户表显式字段通过校验 @1
- 查询口令字段被拒绝 @1
- 通配符查询用户表被拒绝 @1
- 统计用户表条数通过校验 @1
- INTO OUTFILE 写文件被拒绝 @1
- 取值中出现的 into outfile 文本不误判 @1
- 多语句被拒绝 @1
- 非 SELECT 语句被拒绝 @1
- 查询 information_schema 被拒绝 @1
- 查询会话表被拒绝 @1
- 空 SQL 被拒绝 @1
- 合法图表 SQL 通过校验 @1

*/

$testObj = new chartModelTest();
$denyMsg = $lang->chart->sqlSensitiveField;

r($testObj->checkClientSqlTest('select account, realname from zt_user') === true)                      && p() && e(1); // 正常查询用户表显式字段通过校验
r(strpos($testObj->checkClientSqlTest('select account, password from zt_user'), '口令') !== false)     && p() && e(1); // 查询口令字段被拒绝
r($testObj->checkClientSqlTest('select * from zt_user') === $lang->chart->sqlWildcardUser)            && p() && e(1); // 通配符查询用户表被拒绝
r($testObj->checkClientSqlTest('select count(*) from zt_user') === true)                              && p() && e(1); // 统计用户表条数通过校验
r(strpos($testObj->checkClientSqlTest("select '<?php echo 1;?>' INTO OUTFILE '/tmp/x.php'"), 'into outfile') !== false) && p() && e(1); // INTO OUTFILE 写文件被拒绝
r($testObj->checkClientSqlTest("select name from zt_project where name = 'into outfile test'") === true) && p() && e(1); // 取值中出现的 into outfile 文本不误判
r(strpos($testObj->checkClientSqlTest('select account from zt_user; drop table zt_user'), '多条') !== false) && p() && e(1); // 多语句被拒绝
r(strpos($testObj->checkClientSqlTest("update zt_user set password = 'x'"), 'SELECT') !== false)      && p() && e(1); // 非 SELECT 语句被拒绝
r(strpos($testObj->checkClientSqlTest('select table_name from information_schema.tables'), 'information_schema') !== false) && p() && e(1); // 查询 information_schema 被拒绝
r($testObj->checkClientSqlTest('select id from zt_session') === $denyMsg)                              && p() && e(1); // 查询会话表被拒绝
r($testObj->checkClientSqlTest('') === $lang->dev->sqlEmpty)                                          && p() && e(1); // 空 SQL 被拒绝
r($testObj->checkClientSqlTest("SELECT t1.`YEARMONTH`, t1.year, t1.month AS `month` FROM (SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') `YEARMONTH`, Year(date) AS `year`, MONTH(date) AS `month` FROM zt_action) AS t1 GROUP BY t1.year, t1.month LIMIT 999999") === true) && p() && e(1); // 合法图表 SQL 通过校验
