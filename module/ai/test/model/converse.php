#!/usr/bin/env php
<?php

/**

title=测试 aiModel::converse();
timeout=0
cid=15005

- 步骤1：正常对话请求 @0
- 步骤2：ERNIE模型系统消息处理 @0
- 步骤3：空消息数组 @0
- 步骤4：不存在的模型ID @0
- 步骤5：带选项参数的对话 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$ai_model = zenData('ai_model');
$ai_model->id->range('1-5');
$ai_model->type->range('openai{2},ernie{2},claude{1}');
$ai_model->vendor->range('openai{2},baidu{2},anthropic{1}');
$ai_model->credentials->range('{"api_key":"test_key_1"},{"api_key":"test_key_2"},{"api_key":"test_key_3"},{"api_key":"test_key_4"},{"api_key":"test_key_5"}');
$ai_model->name->range('GPT-3.5,GPT-4,ERNIE-Bot,ERNIE-Turbo,Claude-3');
$ai_model->desc->range('OpenAI GPT-3.5,OpenAI GPT-4,Baidu ERNIE-Bot,Baidu ERNIE-Turbo,Anthropic Claude-3');
$ai_model->createdBy->range('admin{5}');
$ai_model->createdDate->range('`2024-01-01`{5}');
$ai_model->enabled->range('1{5}');
$ai_model->deleted->range('0{5}');
$ai_model->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 设置模型配置以避免配置错误
$config = new stdclass();
$config->type = 'openai-gpt35';
$config->vendor = 'openai';
$config->key = 'test_key_1';
$config->id = 1;
$aiTest->setModelConfigTest($config);

// 准备测试消息
$normalMessages = array(
    (object)array('role' => 'user', 'content' => 'Hello, how are you?')
);

$ernieMessages = array(
    (object)array('role' => 'system', 'content' => 'You are a helpful assistant'),
    (object)array('role' => 'user', 'content' => 'Hello')
);

$emptyMessages = array();

$complexMessages = array(
    (object)array('role' => 'system', 'content' => 'You are a helpful assistant'),
    (object)array('role' => 'user', 'content' => 'What is the weather like?'),
    (object)array('role' => 'assistant', 'content' => 'I would need your location to provide weather information.'),
    (object)array('role' => 'user', 'content' => 'I am in Beijing')
);

$options = array('temperature' => 0.7, 'max_tokens' => 100);

// 5. 强制要求：必须包含至少5个测试步骤
r($aiTest->converseTest(1, $normalMessages)) && p() && e('0'); // 步骤1：正常对话请求
r($aiTest->converseTest(3, $ernieMessages)) && p() && e('0'); // 步骤2：ERNIE模型系统消息处理
r($aiTest->converseTest(1, $emptyMessages)) && p() && e('0'); // 步骤3：空消息数组
r($aiTest->converseTest(999, $normalMessages)) && p() && e('0'); // 步骤4：不存在的模型ID
r($aiTest->converseTest(1, $complexMessages, $options)) && p() && e('0'); // 步骤5：带选项参数的对话