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
- 双写转义引号内的内容被完整剥离 @1
- 未闭合的引号也能完整剥离 @1
- 反引号标识符保留 @1
- 无字面量时原样返回 @1
- 行注释内容被剥离 @1
- 井号注释内容被剥离 @1
- 双短横线后无空白不当作注释 @1
- 块注释被替换为空格使注释分隔的关键字中性化 @1
- 引号内的注释符号不当作注释 @1
- 版本注释保留可执行内容 @1

*/

$testObj = new biModelTest();

r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'admin'") === "select id from zt_user where account = ''")            && p() && e("select id from zt_user where account = ''"); // 单引号字面量内容被剥离
r($testObj->stripSqlLiteralsTest('select id from zt_user where account = "admin"') === 'select id from zt_user where account = ""')            && p() && e('select id from zt_user where account = ""'); // 双引号字面量内容被剥离
r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'it\\'s'") === "select id from zt_user where account = ''")           && p() && e("select id from zt_user where account = ''"); // 转义引号内的内容被完整剥离
r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'it''s'") === "select id from zt_user where account = ''")            && p() && e("select id from zt_user where account = ''"); // 双写转义引号内的内容被完整剥离
r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'admin") === "select id from zt_user where account = ''")             && p() && e("select id from zt_user where account = ''"); // 未闭合的引号也能完整剥离
r($testObj->stripSqlLiteralsTest('select `account` from `zt_user`') === 'select `account` from `zt_user`')                                    && p() && e('select `account` from `zt_user`'); // 反引号标识符保留
r($testObj->stripSqlLiteralsTest('select id from zt_user') === 'select id from zt_user')                                                      && p() && e('select id from zt_user'); // 无字面量时原样返回
r($testObj->stripSqlLiteralsTest("select id from zt_user -- where account = 'admin'") === 'select id from zt_user  ')                          && p() && e('select id from zt_user  '); // 行注释内容被剥离
r($testObj->stripSqlLiteralsTest("select id from zt_user # where account = 'admin'")  === 'select id from zt_user  ')                          && p() && e('select id from zt_user  '); // 井号注释内容被剥离
r($testObj->stripSqlLiteralsTest("select id from zt_user where id = 1--1") === 'select id from zt_user where id = 1--1')                       && p() && e('select id from zt_user where id = 1--1'); // 双短横线后无空白不当作注释
r($testObj->stripSqlLiteralsTest("select 'x' into/**/outfile '/tmp/x.php'") === "select '' into outfile ''")                                   && p() && e("select '' into outfile ''"); // 块注释被替换为空格使注释分隔的关键字中性化
r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'a -- b'") === "select id from zt_user where account = ''")           && p() && e("select id from zt_user where account = ''"); // 引号内的注释符号不当作注释
r(strpos($testObj->stripSqlLiteralsTest("select 1 /*!50000 into outfile '/tmp/x.php' */"), 'into outfile') !== false)                           && p() && e(1); // 版本注释保留可执行内容
