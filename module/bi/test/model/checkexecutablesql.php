#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::checkExecutableSql();
timeout=0
cid=0

- 普通查询通过校验 @1
- INTO OUTFILE 被拒绝 @1
- LOAD_FILE 被拒绝 @1
- LOAD XML INFILE 被拒绝 @1
- LOAD DATA LOCAL INFILE 被拒绝 @1
- 字面量中的关键字通过校验 @1
- 注释分隔的 INTO OUTFILE 被拒绝 @1

*/

$testObj = new biModelTest();

r($testObj->checkExecutableSqlTest('select id, account from zt_user') === 1)                      && p() && e(1); // 普通查询通过校验
r($testObj->checkExecutableSqlTest("select 'x' into outfile '/tmp/x.php'") === 0)                 && p() && e(0); // INTO OUTFILE 被拒绝
r($testObj->checkExecutableSqlTest("select load_file('/etc/passwd')") === 0)                      && p() && e(0); // LOAD_FILE 被拒绝
r($testObj->checkExecutableSqlTest("load xml infile '/tmp/x.xml' into table zt_user") === 0)       && p() && e(0); // LOAD XML INFILE 被拒绝
r($testObj->checkExecutableSqlTest("load data local infile '/etc/passwd' into table zt_user") === 0) && p() && e(0); // LOAD DATA LOCAL INFILE 被拒绝
r($testObj->checkExecutableSqlTest("select id from zt_user where account = 'load_file('") === 1)  && p() && e(1); // 字面量中的关键字通过校验
r($testObj->checkExecutableSqlTest("select 'x' into/**/outfile '/tmp/x.php'") === 0)              && p() && e(0); // 注释分隔的 INTO OUTFILE 被拒绝
