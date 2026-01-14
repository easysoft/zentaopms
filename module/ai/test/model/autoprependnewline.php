#!/usr/bin/env php
<?php

/**

title=测试 aiModel::autoPrependNewline();
timeout=0
cid=14997

- 步骤1：空字符串测试 @0
- 步骤2：无换行符文本 @1
- 步骤3：仅末尾换行符 @1
- 步骤4：中间含换行符 @1
- 步骤5：多个换行符 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(strlen($aiTest->autoPrependNewlineTest(''))) && p() && e('0'); // 步骤1：空字符串测试
r(substr_count($aiTest->autoPrependNewlineTest('Hello World'), 'Hello World')) && p() && e('1'); // 步骤2：无换行符文本
r(substr_count($aiTest->autoPrependNewlineTest("Hello World\n"), 'Hello World')) && p() && e('1'); // 步骤3：仅末尾换行符
r(strpos($aiTest->autoPrependNewlineTest("Hello\nWorld"), "\nHello") === 0 ? 1 : 0) && p() && e('1'); // 步骤4：中间含换行符
r(strpos($aiTest->autoPrependNewlineTest("Line1\nLine2\nLine3"), "\nLine1") === 0 ? 1 : 0) && p() && e('1'); // 步骤5：多个换行符