#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::isDangerousSql();
timeout=0
cid=0

- 普通查询不是危险SQL @1
- INTO OUTFILE 写文件是危险SQL @1
- INTO DUMPFILE 写文件是危险SQL @1
- LOAD_FILE 读文件是危险SQL @1
- LOAD DATA INFILE 导入数据是危险SQL @1
- 字符串字面量中的 into outfile 不判定为危险 @1
- 字符串字面量中的 load_file 不判定为危险 @1

*/

$testObj = new biModelTest();

r(!$testObj->isDangerousSqlTest('select id, account from zt_user where deleted = "0"'))                                  && p() && e(0); // 普通查询不是危险SQL
r($testObj->isDangerousSqlTest("select 'x' into outfile '/tmp/x.php'"))                                                    && p() && e(1); // INTO OUTFILE 写文件是危险SQL
r($testObj->isDangerousSqlTest("select 'x' into dumpfile '/tmp/x'"))                                                       && p() && e(1); // INTO DUMPFILE 写文件是危险SQL
r($testObj->isDangerousSqlTest("select load_file('/etc/passwd')"))                                                         && p() && e(1); // LOAD_FILE 读文件是危险SQL
r($testObj->isDangerousSqlTest("load data infile '/tmp/x' into table zt_user"))                                            && p() && e(1); // LOAD DATA INFILE 导入数据是危险SQL
r(!$testObj->isDangerousSqlTest("select id from zt_user where account = 'into outfile'"))                                  && p() && e(0); // 字符串字面量中的 into outfile 不判定为危险
r(!$testObj->isDangerousSqlTest("select id from zt_user where account = 'load_file('"))                                    && p() && e(0); // 字符串字面量中的 load_file 不判定为危险
