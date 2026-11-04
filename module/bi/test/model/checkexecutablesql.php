#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::checkExecutableSql();
timeout=0
cid=0

- 普通查询通过校验 @1
- INTO OUTFILE 被拒绝 @0
- LOAD_FILE 被拒绝 @0
- LOAD XML INFILE 被拒绝 @0
- LOAD DATA LOCAL INFILE 被拒绝 @0
- 字面量中的关键字通过校验 @1
- 注释分隔的 INTO OUTFILE 被拒绝 @0

*/

$testObj = new biModelTest();

r($testObj->checkExecutableSqlTest('select id, account from zt_user'))                      && p() && e(1); // 普通查询通过校验
r($testObj->checkExecutableSqlTest("select 'x' into outfile '/tmp/x.php'"))                 && p() && e(0); // INTO OUTFILE 被拒绝
r($testObj->checkExecutableSqlTest("select load_file('/etc/passwd')"))                      && p() && e(0); // LOAD_FILE 被拒绝
r($testObj->checkExecutableSqlTest("load xml infile '/tmp/x.xml' into table zt_user"))       && p() && e(0); // LOAD XML INFILE 被拒绝
r($testObj->checkExecutableSqlTest("load data local infile '/etc/passwd' into table zt_user")) && p() && e(0); // LOAD DATA LOCAL INFILE 被拒绝
r($testObj->checkExecutableSqlTest("select id from zt_user where account = 'load_file('"))  && p() && e(1); // 字面量中的关键字通过校验
r($testObj->checkExecutableSqlTest("select 'x' into/**/outfile '/tmp/x.php'"))              && p() && e(0); // 注释分隔的 INTO OUTFILE 被拒绝
