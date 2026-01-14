#!/usr/bin/env php
<?php

/**

title=测试 aiModel::assembleRequestData();
timeout=0
cid=14996

- 步骤1：chat类型正常情况，检查模型名属性model @gpt-3.5-turbo
- 步骤2：function类型正常情况，检查function_call参数属性function_call @auto
- 步骤3：completion类型正常情况，检查模型名属性model @gpt-3.5-turbo-instruct
- 步骤4：缺少必需参数messages，应该返回false @0
- 步骤5：包含可选参数temperature属性temperature @0.8

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 创建测试数据表（assembleRequestData不直接操作数据库，所以这里不需要zenData）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 需要先设置模型配置才能测试assembleRequestData
$config = new stdclass();
$config->type = 'openai-gpt35';
$aiTest->setModelConfigTest($config);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->assembleRequestDataTest('chat', (object)array('messages' => array(array('role' => 'user', 'content' => 'test'))))) && p('model') && e('gpt-3.5-turbo'); // 步骤1：chat类型正常情况，检查模型名
r($aiTest->assembleRequestDataTest('function', (object)array('messages' => array(array('role' => 'user', 'content' => 'test')), 'functions' => array(), 'function_call' => 'auto'))) && p('function_call') && e('auto'); // 步骤2：function类型正常情况，检查function_call参数
r($aiTest->assembleRequestDataTest('completion', (object)array('prompt' => 'test prompt', 'max_tokens' => 100))) && p('model') && e('gpt-3.5-turbo-instruct'); // 步骤3：completion类型正常情况，检查模型名
r($aiTest->assembleRequestDataTest('chat', (object)array('temperature' => 0.8))) && p() && e('0'); // 步骤4：缺少必需参数messages，应该返回false
r($aiTest->assembleRequestDataTest('chat', (object)array('messages' => array(array('role' => 'user', 'content' => 'test')), 'temperature' => 0.8, 'max_tokens' => 100))) && p('temperature') && e('0.8'); // 步骤5：包含可选参数temperature