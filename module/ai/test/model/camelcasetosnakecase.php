#!/usr/bin/env php
<?php

/**

title=测试 aiModel::camelCaseToSnakeCase();
timeout=0
cid=14998

- 步骤1：正常驼峰命名转换 @camel_case_string
- 步骤2：单个单词输入 @singleword
- 步骤3：已经是下划线命名 @already_snake_case
- 步骤4：空字符串输入 @0
- 步骤5：包含数字的驼峰命名 @camel_case123_string

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->camelCaseToSnakeCaseTest('camelCaseString')) && p() && e('camel_case_string'); // 步骤1：正常驼峰命名转换
r($aiTest->camelCaseToSnakeCaseTest('singleword')) && p() && e('singleword'); // 步骤2：单个单词输入
r($aiTest->camelCaseToSnakeCaseTest('already_snake_case')) && p() && e('already_snake_case'); // 步骤3：已经是下划线命名
r($aiTest->camelCaseToSnakeCaseTest('')) && p() && e('0'); // 步骤4：空字符串输入
r($aiTest->camelCaseToSnakeCaseTest('camelCase123String')) && p() && e('camel_case123_string'); // 步骤5：包含数字的驼峰命名