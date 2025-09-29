#!/usr/bin/env php
<?php

/**

title=测试 biModel::checkDuckDBFile();
timeout=0
cid=0

- 执行biTest模块的checkDuckDBFileTest方法，参数是$testPath, array  @1
- 执行biTest模块的checkDuckDBFileTest方法，参数是$testPath, array  @1
- 执行biTest模块的checkDuckDBFileTest方法，参数是'', array  @1
- 执行biTest模块的checkDuckDBFileTest方法，参数是$testPath, array  @1
- 执行biTest模块的checkDuckDBFileTest方法，参数是$testPath, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

$biTest = new biTest();

// 创建临时测试文件和目录
$testPath = sys_get_temp_dir() . '/zentao_test_duckdb/';
if(!is_dir($testPath)) mkdir($testPath, 0755, true);

// 创建测试文件
$testFile = $testPath . 'duckdb.exe';
$testExtFile = $testPath . 'test.extension';
file_put_contents($testFile, 'test content');
file_put_contents($testExtFile, 'extension content');
chmod($testFile, 0755);

r(is_object($biTest->checkDuckDBFileTest($testPath, array('file' => 'duckdb.exe', 'extension' => 'test.extension')))) && p() && e('1');
r($biTest->checkDuckDBFileTest($testPath, array('file' => 'nonexistent.exe', 'extension' => 'nonexistent.ext')) === false) && p() && e('1');
r($biTest->checkDuckDBFileTest('', array('file' => 'duckdb.exe', 'extension' => 'test.extension')) === false) && p() && e('1');
r($biTest->checkDuckDBFileTest($testPath, array()) === false) && p() && e('1');
r($biTest->checkDuckDBFileTest($testPath, array('extension' => 'test.extension')) === false) && p() && e('1');

// 清理测试文件
unlink($testFile);
unlink($testExtFile);
rmdir($testPath);