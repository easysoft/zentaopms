#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::stripSqlLiterals();
timeout=0
cid=0

- 单引号字面量内容被剥离 @select id from zt_user where account = ''
- 双引号字面量内容被剥离 @select id from zt_user where account = ""
- 转义引号内的内容被完整剥离 @select id from zt_user where account = ''
- 双写转义引号内的内容被完整剥离 @select id from zt_user where account = ''
- 未闭合的引号也能完整剥离 @select id from zt_user where account = ''
- 反引号标识符保留 @select `account` from `zt_user`
- 无字面量时原样返回 @select id from zt_user
- 行注释内容被剥离 @1
- 井号注释内容被剥离 @1
- 双短横线后无空白不当作注释 @select id from zt_user where id = 1--1
- 块注释被替换为空格使注释分隔的关键字中性化 @select '' into outfile ''
- 引号内的注释符号不当作注释 @select id from zt_user where account = ''
- 版本注释保留可执行内容 @1

*/

$testObj = new biModelTest();

r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'admin'"))            && p() && e("select id from zt_user where account = ''"); // 单引号字面量内容被剥离
r($testObj->stripSqlLiteralsTest('select id from zt_user where account = "admin"'))            && p() && e('select id from zt_user where account = ""'); // 双引号字面量内容被剥离
r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'it\\'s'"))           && p() && e("select id from zt_user where account = ''"); // 转义引号内的内容被完整剥离
r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'it''s'"))            && p() && e("select id from zt_user where account = ''"); // 双写转义引号内的内容被完整剥离
r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'admin"))             && p() && e("select id from zt_user where account = ''"); // 未闭合的引号也能完整剥离
r($testObj->stripSqlLiteralsTest('select `account` from `zt_user`'))                           && p() && e('select `account` from `zt_user`'); // 反引号标识符保留
r($testObj->stripSqlLiteralsTest('select id from zt_user'))                                    && p() && e('select id from zt_user'); // 无字面量时原样返回
r($testObj->stripSqlLiteralsTest("select id from zt_user -- where account = 'admin'") === 'select id from zt_user  ') && p() && e(1); // 行注释内容被剥离
r($testObj->stripSqlLiteralsTest("select id from zt_user # where account = 'admin'") === 'select id from zt_user  ')  && p() && e(1); // 井号注释内容被剥离
r($testObj->stripSqlLiteralsTest("select id from zt_user where id = 1--1"))                    && p() && e('select id from zt_user where id = 1--1'); // 双短横线后无空白不当作注释
r($testObj->stripSqlLiteralsTest("select 'x' into/**/outfile '/tmp/x.php'"))                   && p() && e("select '' into outfile ''"); // 块注释被替换为空格使注释分隔的关键字中性化
r($testObj->stripSqlLiteralsTest("select id from zt_user where account = 'a -- b'"))           && p() && e("select id from zt_user where account = ''"); // 引号内的注释符号不当作注释
r(strpos($testObj->stripSqlLiteralsTest("select 1 /*!50000 into outfile '/tmp/x.php' */"), 'into outfile') !== false) && p() && e(1); // 版本注释保留可执行内容
