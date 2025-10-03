#!/usr/bin/env php
<?php

/**

title=测试 biModel::checkDuckDBFile();
timeout=0
cid=15152

- 正常情况：文件和扩展都存在且可执行 >> 期望返回object
- 文件不存在的情况 >> 期望返回false
- 路径为空的情况 >> 期望返回false
- bin参数为空数组的情况 >> 期望返回false
- bin参数缺少file键的情况 >> 期望返回false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

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