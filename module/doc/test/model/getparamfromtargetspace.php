#!/usr/bin/env php
<?php

/**

title=测试 docModel::getParamFromTargetSpace();
timeout=0
cid=16121

- 步骤1：正常情况提取类型 @product
- 步骤2：正常情况提取ID @1
- 步骤3：空字符串输入 @0
- 步骤4：无点号字符串提取ID @0
- 步骤5：多点号情况提取类型 @execution
- 步骤6：多点号情况提取ID @5
- 步骤7：mine类型测试 @mine
- 步骤8：custom类型ID提取 @123

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->getParamFromTargetSpaceTest('product.1', 'type')) && p() && e('product'); // 步骤1：正常情况提取类型
r($docTest->getParamFromTargetSpaceTest('product.1', 'id')) && p() && e('1'); // 步骤2：正常情况提取ID
r($docTest->getParamFromTargetSpaceTest('', 'type')) && p() && e('0'); // 步骤3：空字符串输入
r($docTest->getParamFromTargetSpaceTest('project', 'id')) && p() && e('0'); // 步骤4：无点号字符串提取ID
r($docTest->getParamFromTargetSpaceTest('execution.5.test', 'type')) && p() && e('execution'); // 步骤5：多点号情况提取类型
r($docTest->getParamFromTargetSpaceTest('execution.5.test', 'id')) && p() && e('5'); // 步骤6：多点号情况提取ID
r($docTest->getParamFromTargetSpaceTest('mine.0', 'type')) && p() && e('mine'); // 步骤7：mine类型测试
r($docTest->getParamFromTargetSpaceTest('custom.123', 'id')) && p() && e('123'); // 步骤8：custom类型ID提取