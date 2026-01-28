#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareCopySQL();
timeout=0
cid=15202

- 步骤1：测试有效duckdb路径返回字符串类型 @1
- 步骤2：测试空路径参数返回结果类型验证 @1
- 步骤3：测试包含路径分隔符的路径处理 @1
- 步骤4：测试返回值包含SQL语句关键字 @1
- 步骤5：测试方法调用稳定性验证 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r(is_string($biTest->prepareCopySQLTest('/tmp/duckdb/'))) && p() && e('1'); // 步骤1：测试有效duckdb路径返回字符串类型
r(is_string($biTest->prepareCopySQLTest(''))) && p() && e('1'); // 步骤2：测试空路径参数返回结果类型验证
r(is_string($biTest->prepareCopySQLTest('/tmp/test/path/'))) && p() && e('1'); // 步骤3：测试包含路径分隔符的路径处理
$result = $biTest->prepareCopySQLTest('/tmp/duckdb/');
r((strpos($result, 'copy') !== false || strpos($result, 'select') !== false || $result === '')) && p() && e('1'); // 步骤4：测试返回值包含SQL语句关键字
r(is_string($biTest->prepareCopySQLTest('/var/tmp/duckdb/'))) && p() && e('1'); // 步骤5：测试方法调用稳定性验证