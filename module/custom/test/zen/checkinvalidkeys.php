#!/usr/bin/env php
<?php
/**

title=测试 customZen::checkInvalidKeys();
timeout=0
cid=0

- 步骤1：正常数字键值 @1
- 步骤2：超过255的数字键值 @invalid_number_key
- 步骤3：包含特殊字符 @invalid_string_key
- 步骤4：超过10字符长度 @invalid_strlen_ten
- 步骤5：超过15字符长度 @invalid_strlen_fifteen

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('lang');

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$customTest = new customTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($customTest->checkInvalidKeysTest(array('1', '2', '100'), 'story', 'priList')) && p() && e(1);  // 步骤1：正常数字键值
r($customTest->checkInvalidKeysTest(array('256', '300'), 'story', 'priList')) && p() && e('invalid_number_key');  // 步骤2：超过255的数字键值
r($customTest->checkInvalidKeysTest(array('invalid-key', 'test@key'), 'story', 'statusList')) && p() && e('invalid_string_key');  // 步骤3：包含特殊字符
r($customTest->checkInvalidKeysTest(array('verylongkeyname'), 'user', 'roleList')) && p() && e('invalid_strlen_ten');  // 步骤4：超过10字符长度
r($customTest->checkInvalidKeysTest(array('verylongtypelistkey'), 'todo', 'typeList')) && p() && e('invalid_strlen_fifteen');  // 步骤5：超过15字符长度
