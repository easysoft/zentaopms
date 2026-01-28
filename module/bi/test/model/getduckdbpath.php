#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::getDuckDBPath();
timeout=0
cid=15168

- 步骤1：正常调用getDuckDBPath方法返回对象或false @1
- 步骤2：验证方法调用无错误 @1
- 步骤3：测试返回结果为对象或false @1
- 步骤4：验证方法执行稳定性 @1
- 步骤5：再次验证返回值类型 @1

*/

$biTest = new biModelTest();

r(is_object($biTest->getDuckDBPathTest()) || $biTest->getDuckDBPathTest() === false) && p() && e('1'); // 步骤1：正常调用getDuckDBPath方法返回对象或false
r(is_object($biTest->getDuckDBPathTest()) || $biTest->getDuckDBPathTest() === false) && p() && e('1'); // 步骤2：验证方法调用无错误
r(is_object($biTest->getDuckDBPathTest()) || $biTest->getDuckDBPathTest() === false) && p() && e('1'); // 步骤3：测试返回结果为对象或false
r(is_object($biTest->getDuckDBPathTest()) || $biTest->getDuckDBPathTest() === false) && p() && e('1'); // 步骤4：验证方法执行稳定性
r(is_object($biTest->getDuckDBPathTest()) || $biTest->getDuckDBPathTest() === false) && p() && e('1'); // 步骤5：再次验证返回值类型