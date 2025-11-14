#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareSyncCommand();
timeout=0
cid=15208

- 执行biTest模块的prepareSyncCommandTest方法，参数是$binPath, $extensionPath, $copySQL  @1
- 执行$result, $binPath) !== false @1
- 执行$result, $extensionPath) !== false @1
- 执行$result, $copySQL) !== false @1
- 执行$result, '2>&1') !== false @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 测试数据
$binPath = '/usr/bin/duckdb';
$extensionPath = '/opt/mysql_scanner.duckdb_extension';
$copySQL = 'copy (select * from zt_user) to \'/tmp/user.parquet\'';

// 步骤1：测试返回字符串类型
r(is_string($biTest->prepareSyncCommandTest($binPath, $extensionPath, $copySQL))) && p() && e('1');

// 步骤2：测试命令包含binPath路径
$result = $biTest->prepareSyncCommandTest($binPath, $extensionPath, $copySQL);
r(strpos($result, $binPath) !== false) && p() && e('1');

// 步骤3：测试命令包含extensionPath路径
r(strpos($result, $extensionPath) !== false) && p() && e('1');

// 步骤4：测试命令包含copySQL内容
r(strpos($result, $copySQL) !== false) && p() && e('1');

// 步骤5：测试命令包含重定向符号
r(strpos($result, '2>&1') !== false) && p() && e('1');