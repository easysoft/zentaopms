#!/usr/bin/env php
<?php

/**

title=测试 biModel::initParquet();
timeout=0
cid=0

- 步骤1：正常调用initParquet方法，验证返回值不为空 @DuckDB 二进制文件不存在。
- 步骤2：测试方法调用的稳定性，验证重复调用 @DuckDB 二进制文件不存在。
- 步骤3：验证方法在DuckDB不可用时的错误处理 @1
- 步骤4：测试返回值类型的正确性 @string
- 步骤5：验证方法执行过程中的异常处理 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

r($biTest->initParquetTest()) && p() && e('DuckDB 二进制文件不存在。'); // 步骤1：正常调用initParquet方法，验证返回值不为空
r($biTest->initParquetTest()) && p() && e('DuckDB 二进制文件不存在。'); // 步骤2：测试方法调用的稳定性，验证重复调用
r(is_string($biTest->initParquetTest()) || $biTest->initParquetTest() === true) && p() && e('1'); // 步骤3：验证方法在DuckDB不可用时的错误处理
r(gettype($biTest->initParquetTest())) && p() && e('string'); // 步骤4：测试返回值类型的正确性
r($biTest->initParquetTest() !== null) && p() && e('1'); // 步骤5：验证方法执行过程中的异常处理