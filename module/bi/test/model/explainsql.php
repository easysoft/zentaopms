#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::explainSQL();
timeout=0
cid=15157

- 使用有效SQL和mysql驱动属性result @success
- 使用有效SQL和duckdb驱动属性result @success
- 使用无效SQL属性result @fail
- 使用空SQL属性result @fail
- 不指定驱动使用默认mysql属性result @success
- 复杂SQL语句属性result @success
- INTO OUTFILE 写文件被拒绝 @SQL 语句包含危险语法（写文件/读文件），请求已被拒绝。

*/

$biTest = new biModelTest();

r($biTest->explainSQLTest('SELECT * FROM zt_user WHERE id = 1', 'mysql')) && p('result') && e('success'); // 使用有效SQL和mysql驱动
r($biTest->explainSQLTest('SELECT * FROM invalid_table WHERE', 'mysql')) && p('result') && e('fail'); // 使用无效SQL
r($biTest->explainSQLTest('', 'mysql')) && p('result') && e('fail'); // 使用空SQL
r($biTest->explainSQLTest('SELECT id, account FROM zt_user LIMIT 10')) && p('result') && e('success'); // 不指定驱动使用默认mysql
r($biTest->explainSQLTest('SELECT u.id, u.account, u.realname FROM zt_user u WHERE u.deleted = "0" ORDER BY u.id', 'mysql')) && p('result') && e('success'); // 复杂SQL语句
r($biTest->explainSQLTest("SELECT 'x' INTO OUTFILE '/tmp/zentao_vuln010.php'", 'mysql')) && p() && e($lang->bi->errorIllegalSql); // INTO OUTFILE 写文件被拒绝
