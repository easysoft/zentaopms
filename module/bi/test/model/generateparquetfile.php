#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::generateParquetFile();
timeout=0
cid=15158

- 步骤1：正常调用generateParquetFile方法，检查返回值类型 @1
- 步骤2：验证方法执行不会抛出异常 @1
- 步骤3：测试返回值为字符串或true @1
- 步骤4：验证方法调用稳定性 @1
- 步骤5：再次测试返回值类型一致性 @1

*/

$biTest = new biModelTest();

r(is_string($biTest->generateParquetFileTest()) || $biTest->generateParquetFileTest() === true) && p() && e('1'); // 步骤1：正常调用generateParquetFile方法，检查返回值类型
r(is_string($biTest->generateParquetFileTest()) || $biTest->generateParquetFileTest() === true) && p() && e('1'); // 步骤2：验证方法执行不会抛出异常
r(is_string($biTest->generateParquetFileTest()) || $biTest->generateParquetFileTest() === true) && p() && e('1'); // 步骤3：测试返回值为字符串或true
r(is_string($biTest->generateParquetFileTest()) || $biTest->generateParquetFileTest() === true) && p() && e('1'); // 步骤4：验证方法调用稳定性
r(is_string($biTest->generateParquetFileTest()) || $biTest->generateParquetFileTest() === true) && p() && e('1'); // 步骤5：再次测试返回值类型一致性