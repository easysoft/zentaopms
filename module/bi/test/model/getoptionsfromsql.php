#!/usr/bin/env php
<?php

/**

title=测试 biModel::getOptionsFromSql();
timeout=0
cid=15177

- 执行biTest模块的getOptionsFromSqlTest方法，参数是'SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'id', 'account' 属性1 @admin
- 执行biTest模块的getOptionsFromSqlTest方法，参数是'SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'id', 'account' 属性2 @user1
- 执行biTest模块的getOptionsFromSqlTest方法，参数是'SELECT id, realname FROM zt_user WHERE deleted = "0"', 'mysql', 'id', 'realname' 属性1 @admin
- 执行biTest模块的getOptionsFromSqlTest方法，参数是'SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'nonexistent', 'account'  @0
- 执行biTest模块的getOptionsFromSqlTest方法，参数是'SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'id', 'nonexistent'  @0
- 执行biTest模块的getOptionsFromSqlTest方法，参数是'SELECT count 属性10 @total
- INTO OUTFILE 写文件被拒绝 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
su('admin');

$biTest = new biModelTest();

r($biTest->getOptionsFromSqlTest('SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'id', 'account')) && p('1') && e('admin');
r($biTest->getOptionsFromSqlTest('SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'id', 'account')) && p('2') && e('user1');
r($biTest->getOptionsFromSqlTest('SELECT id, realname FROM zt_user WHERE deleted = "0"', 'mysql', 'id', 'realname')) && p('1') && e('admin');
r(count($biTest->getOptionsFromSqlTest('SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'nonexistent', 'account'))) && p() && e(0);
r(count($biTest->getOptionsFromSqlTest('SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'id', 'nonexistent'))) && p() && e(0);
r($biTest->getOptionsFromSqlTest('SELECT count(*) as cnt, "total" as label FROM zt_user', 'mysql', 'cnt', 'label')) && p('10') && e('total');
r($biTest->getOptionsFromSqlTest("SELECT 'x' INTO OUTFILE '/tmp/zentao_vuln010.php'", 'mysql', 'x', 'x') === $lang->bi->errorIllegalSql) && p() && e(1); // INTO OUTFILE 写文件被拒绝
