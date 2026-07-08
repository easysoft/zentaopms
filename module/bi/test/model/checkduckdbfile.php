#!/usr/bin/env php
<?php

/**

title=测试 biModel::checkDuckDBFile();
timeout=0
cid=15152

- 步骤1：返回对象 @1
- 步骤2：返回bin路径 @1
- 步骤3：返回extension路径 @1
- 步骤4：文件不存在时返回失败 @0
- 步骤5：路径为空时返回失败 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$biTest = new biModelTest();

$testPath = sys_get_temp_dir() . '/zentao_test_duckdb_' . uniqid() . '/';
if(!is_dir($testPath)) mkdir($testPath, 0755, true);

$testFile    = $testPath . 'duckdb.exe';
$testExtFile = $testPath . 'test.extension';
$binConfig   = array('file' => 'duckdb.exe', 'extension' => 'test.extension');
file_put_contents($testFile, 'test content');
file_put_contents($testExtFile, 'extension content');
chmod($testFile, 0755);

$result = $biTest->checkDuckDBFileTest($testPath, $binConfig);

r(is_object($result)) && p() && e('1');
r($result->bin === $testFile) && p() && e('1');
r($result->extension === $testExtFile) && p() && e('1');
r($biTest->checkDuckDBFileTest($testPath, array('file' => 'nonexistent.exe', 'extension' => 'nonexistent.ext'))) && p() && e('0');
r($biTest->checkDuckDBFileTest('', $binConfig)) && p() && e('0');

if(file_exists($testFile)) unlink($testFile);
if(file_exists($testExtFile)) unlink($testExtFile);
if(is_dir($testPath)) rmdir($testPath);
