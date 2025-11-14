#!/usr/bin/env php
<?php

/**

title=测试 transferModel::checkTmpFile();
timeout=0
cid=19309

- 步骤1：正常情况，文件存在且maxImport设置 @1
- 步骤2：边界值，没有session文件名 @0
- 步骤3：异常情况，临时文件不存在 @0
- 步骤4：边界值，没有maxImport @0
- 步骤5：正常情况，默认测试场景 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transfer.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$transferTest = new transferTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($transferTest->checkTmpFileTest('file_exists')) && p() && e('1'); // 步骤1：正常情况，文件存在且maxImport设置
r($transferTest->checkTmpFileTest('no_session')) && p() && e('0'); // 步骤2：边界值，没有session文件名
r($transferTest->checkTmpFileTest('no_file')) && p() && e('0'); // 步骤3：异常情况，临时文件不存在
r($transferTest->checkTmpFileTest('no_maxImport')) && p() && e('0'); // 步骤4：边界值，没有maxImport
r($transferTest->checkTmpFileTest()) && p() && e('1'); // 步骤5：正常情况，默认测试场景