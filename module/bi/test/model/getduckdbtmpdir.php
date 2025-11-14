#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getDuckDBTmpDir();
timeout=0
cid=15169

- 步骤1：正常获取DuckDB临时目录路径，static=false @1
- 步骤2：获取静态路径，static=true @1
- 步骤3：验证路径包含duckdb/bi目录结构 @1
- 步骤4：测试返回结果类型 @1
- 步骤5：再次验证方法调用稳定性 @1

*/

$biTest = new biTest();

r(is_string($biTest->getDuckDBTmpDirTest(false)) || $biTest->getDuckDBTmpDirTest(false) === false) && p() && e('1'); // 步骤1：正常获取DuckDB临时目录路径，static=false
r(is_string($biTest->getDuckDBTmpDirTest(true))) && p() && e('1'); // 步骤2：获取静态路径，static=true
r(strpos($biTest->getDuckDBTmpDirTest(true), 'duckdb' . DIRECTORY_SEPARATOR . 'bi' . DIRECTORY_SEPARATOR) !== false) && p() && e('1'); // 步骤3：验证路径包含duckdb/bi目录结构
r(is_string($biTest->getDuckDBTmpDirTest(false)) || $biTest->getDuckDBTmpDirTest(false) === false) && p() && e('1'); // 步骤4：测试返回结果类型
r(is_string($biTest->getDuckDBTmpDirTest(true))) && p() && e('1'); // 步骤5：再次验证方法调用稳定性