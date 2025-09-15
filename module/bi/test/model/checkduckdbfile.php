#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::checkDuckDBFile();
timeout=0
cid=0

- 步骤1：测试正常文件和扩展存在的情况 @1
- 步骤2：测试文件不存在且扩展不存在的情况返回false @1
- 步骤3：测试路径为空的情况返回false @1
- 步骤4：测试bin参数为空数组的情况返回false @1
- 步骤5：测试bin参数缺少file字段的情况返回false @1

*/

$biTest = new biTest();

// 创建临时测试文件和目录
$testPath = sys_get_temp_dir() . '/zentao_test_duckdb/';
if(!is_dir($testPath)) mkdir($testPath, 0755, true);

// 创建测试文件
$testFile = $testPath . 'duckdb.exe';
$testExtFile = $testPath . 'test.extension';
file_put_contents($testFile, 'test content');
file_put_contents($testExtFile, 'extension content');
chmod($testFile, 0755); // 设置可执行权限

r(is_object($biTest->checkDuckDBFileTest($testPath, array('file' => 'duckdb.exe', 'extension' => 'test.extension')))) && p() && e('1'); // 步骤1：测试正常文件和扩展存在的情况
r($biTest->checkDuckDBFileTest($testPath, array('file' => 'nonexistent.exe', 'extension' => 'nonexistent.ext')) === false) && p() && e('1'); // 步骤2：测试文件不存在且扩展不存在的情况返回false
r($biTest->checkDuckDBFileTest('', array('file' => 'duckdb.exe', 'extension' => 'test.extension')) === false) && p() && e('1'); // 步骤3：测试路径为空的情况返回false
r($biTest->checkDuckDBFileTest($testPath, array()) === false) && p() && e('1'); // 步骤4：测试bin参数为空数组的情况返回false
r($biTest->checkDuckDBFileTest($testPath, array('extension' => 'test.extension')) === false) && p() && e('1'); // 步骤5：测试bin参数缺少file字段的情况返回false

// 清理测试文件
unlink($testFile);
unlink($testExtFile);
rmdir($testPath);