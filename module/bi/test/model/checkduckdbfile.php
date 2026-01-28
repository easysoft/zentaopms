#!/usr/bin/env php
<?php

/**

title=测试 biModel::checkDuckDBFile();
timeout=0
cid=15152

- 执行biTest模块的checkDuckDBFileTest方法，参数是$testPath, array  @object
- 执行biTest模块的checkDuckDBFileTest方法，参数是$testPath, array  @0
- 执行biTest模块的checkDuckDBFileTest方法，参数是'', array  @0
- 执行biTest模块的checkDuckDBFileTest方法，参数是$testPath, array  @0
- 执行biTest模块的checkDuckDBFileTest方法，参数是$testPath, array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$biTest = new biModelTest();

$testPath = sys_get_temp_dir() . '/zentao_test_duckdb_' . uniqid() . '/';
if(!is_dir($testPath)) mkdir($testPath, 0755, true);

$testFile = $testPath . 'duckdb.exe';
$testExtFile = $testPath . 'test.extension';
file_put_contents($testFile, 'test content');
file_put_contents($testExtFile, 'extension content');
chmod($testFile, 0755);

r($biTest->checkDuckDBFileTest($testPath, array('file' => 'duckdb.exe', 'extension' => 'test.extension'))) && p() && e('object');
r($biTest->checkDuckDBFileTest($testPath, array('file' => 'nonexistent.exe', 'extension' => 'nonexistent.ext'))) && p() && e('0');
r($biTest->checkDuckDBFileTest('', array('file' => 'duckdb.exe', 'extension' => 'test.extension'))) && p() && e('0');
r($biTest->checkDuckDBFileTest($testPath, array())) && p() && e('0');
r($biTest->checkDuckDBFileTest($testPath, array('extension' => 'test.extension'))) && p() && e('0');

if(file_exists($testFile)) unlink($testFile);
if(file_exists($testExtFile)) unlink($testExtFile);
if(is_dir($testPath)) rmdir($testPath);