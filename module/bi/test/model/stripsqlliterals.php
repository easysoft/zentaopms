#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::stripSqlLiterals();
timeout=0
cid=0

- 单引号字面量内容被剥离 @1
- 双引号字面量内容被剥离 @1
- 转义引号内的内容被完整剥离 @1
- 反引号标识符保留 @1
- 无字面量时原样返回 @1

*/

$testObj = new biModelTest();

r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'admin'") === "select id from zt_user where account = ''")            && p() && e("select id from zt_user where account = ''"); // 单引号字面量内容被剥离
r($testObj->stripSqlLiteralsTest('select id from zt_user where account = "admin"') === 'select id from zt_user where account = ""')            && p() && e('select id from zt_user where account = ""'); // 双引号字面量内容被剥离
r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'it\\'s'") === "select id from zt_user where account = ''")           && p() && e("select id from zt_user where account = ''"); // 转义引号内的内容被完整剥离
r($testObj->stripSqlLiteralsTest('select `account` from `zt_user`') === 'select `account` from `zt_user`')                                    && p() && e('select `account` from `zt_user`'); // 反引号标识符保留
r($testObj->stripSqlLiteralsTest('select id from zt_user') === 'select id from zt_user')                                                      && p() && e('select id from zt_user'); // 无字面量时原样返回
