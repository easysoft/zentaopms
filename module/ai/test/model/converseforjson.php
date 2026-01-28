#!/usr/bin/env php
<?php

/**

title=测试 aiModel::converseForJSON();
timeout=0
cid=15006

- 步骤1：有效模型ID、有效消息、有效schema @0
- 步骤2：无效模型ID、有效消息、有效schema @0
- 步骤3：有效模型ID、空消息数组、有效schema @0
- 步骤4：有效模型ID、有效消息、无效schema @0
- 步骤5：负数模型ID、有效消息、有效schema @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 准备测试数据
$validMessages = array(
    (object)array('role' => 'user', 'content' => 'Generate a user profile with name and age')
);

$validSchema = (object)array(
    'type' => 'object',
    'properties' => (object)array(
        'name' => (object)array('type' => 'string'),
        'age' => (object)array('type' => 'integer')
    ),
    'required' => array('name', 'age')
);

$invalidSchema = array();
$emptyMessages = array();

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($aiTest->converseForJSONTest(1, $validMessages, $validSchema)) && p() && e('0'); // 步骤1：有效模型ID、有效消息、有效schema
r($aiTest->converseForJSONTest(999, $validMessages, $validSchema)) && p() && e('0'); // 步骤2：无效模型ID、有效消息、有效schema
r($aiTest->converseForJSONTest(1, $emptyMessages, $validSchema)) && p() && e('0'); // 步骤3：有效模型ID、空消息数组、有效schema
r($aiTest->converseForJSONTest(1, $validMessages, $invalidSchema)) && p() && e('0'); // 步骤4：有效模型ID、有效消息、无效schema
r($aiTest->converseForJSONTest(-1, $validMessages, $validSchema)) && p() && e('0'); // 步骤5：负数模型ID、有效消息、有效schema
