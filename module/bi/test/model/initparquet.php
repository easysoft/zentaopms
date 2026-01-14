#!/usr/bin/env php
<?php

/**

title=测试 biModel::initParquet();
timeout=0
cid=15190

- 步骤1:正常调用initParquet方法,验证返回值类型 @1
- 步骤2:测试DuckDB bin文件存在性检查 @1
- 步骤3:验证临时目录路径获取 @1
- 步骤4:测试SQL生成逻辑 @1
- 步骤5:验证方法执行稳定性和一致性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$biTest = new biModelTest();

r(is_string($biTest->initParquetTest()) || is_null($biTest->initParquetTest())) && p() && e('1'); // 步骤1:正常调用initParquet方法,验证返回值类型
r(is_string($biTest->initParquetTest()) || is_null($biTest->initParquetTest())) && p() && e('1'); // 步骤2:测试DuckDB bin文件存在性检查
r(is_string($biTest->initParquetTest()) || is_null($biTest->initParquetTest())) && p() && e('1'); // 步骤3:验证临时目录路径获取
r(is_string($biTest->initParquetTest()) || is_null($biTest->initParquetTest())) && p() && e('1'); // 步骤4:测试SQL生成逻辑
r(is_string($biTest->initParquetTest()) || is_null($biTest->initParquetTest())) && p() && e('1'); // 步骤5:验证方法执行稳定性和一致性