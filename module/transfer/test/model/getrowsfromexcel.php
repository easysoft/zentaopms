#!/usr/bin/env php
<?php

/**

title=测试 transferModel::getRowsFromExcel();
timeout=0
cid=19319

- 步骤1：正常文件读取 @array
- 步骤2：文件读取错误 @false
- 步骤3：空文件名 @false
- 步骤4：文件不存在 @false
- 步骤5：错误清理机制 @false

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$transferTest = new transferModelTest();

// 4. 测试步骤（必须包含至少5个测试步骤）
r($transferTest->getRowsFromExcelTest('valid_file')) && p('0') && e('array'); // 步骤1：正常文件读取
r($transferTest->getRowsFromExcelTest('file_error')) && p() && e('false'); // 步骤2：文件读取错误
r($transferTest->getRowsFromExcelTest('empty_filename')) && p() && e('false'); // 步骤3：空文件名
r($transferTest->getRowsFromExcelTest('not_exists')) && p() && e('false'); // 步骤4：文件不存在
r($transferTest->getRowsFromExcelTest('cleanup_test')) && p() && e('false'); // 步骤5：错误清理机制