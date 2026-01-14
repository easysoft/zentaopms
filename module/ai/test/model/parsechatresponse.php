#!/usr/bin/env php
<?php

/**

title=测试 aiModel::parseChatResponse();
timeout=0
cid=15058

- 执行aiTest模块的parseChatResponseTest方法，参数是$ernieResponse  @这是文心一言的回复消息
- 执行aiTest模块的parseChatResponseTest方法，参数是$openaiResponse  @Hello! How can I help you today?
- 执行aiTest模块的parseChatResponseTest方法，参数是$failResponse  @0
- 执行aiTest模块的parseChatResponseTest方法，参数是$ernieNoResultResponse  @0
- 执行aiTest模块的parseChatResponseTest方法，参数是$openaiNoChoicesResponse  @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$aiTest = new aiModelTest();

// 4. 模拟设置为ERNIE模型类型
$aiTest->objectModel->modelConfig = new stdClass();
$aiTest->objectModel->modelConfig->type = 'baidu-ernie';

// 测试步骤
// 步骤1：测试ERNIE模型正常响应解析
$ernieResponse = new stdClass();
$ernieResponse->result = 'success';
$ernieResponse->content = '{"result":"这是文心一言的回复消息","id":"as-bcfa043ctv","object":"chat.completion","created":1681277883,"sentence_id":0}';
r($aiTest->parseChatResponseTest($ernieResponse)) && p('0') && e('这是文心一言的回复消息');

// 步骤2：测试OpenAI模型正常响应解析
$aiTest->objectModel->modelConfig->type = 'openai-gpt35';
$openaiResponse = new stdClass();
$openaiResponse->result = 'success';
$openaiResponse->content = '{"id":"chatcmpl-123","object":"chat.completion","created":1677652288,"model":"gpt-3.5-turbo","choices":[{"index":0,"message":{"role":"assistant","content":"Hello! How can I help you today?"},"finish_reason":"stop"},{"index":1,"message":{"role":"assistant","content":"Another response message"},"finish_reason":"stop"}],"usage":{"prompt_tokens":9,"completion_tokens":12,"total_tokens":21}}';
r($aiTest->parseChatResponseTest($openaiResponse)) && p('0') && e('Hello! How can I help you today?');

// 步骤3：测试失败响应处理
$failResponse = new stdClass();
$failResponse->result = 'fail';
$failResponse->message = 'API request failed';
r($aiTest->parseChatResponseTest($failResponse)) && p() && e('0');

// 步骤4：测试ERNIE模型缺少result字段的响应
$aiTest->objectModel->modelConfig->type = 'baidu-ernie';
$ernieNoResultResponse = new stdClass();
$ernieNoResultResponse->result = 'success';
$ernieNoResultResponse->content = '{"id":"as-bcfa043ctv","object":"chat.completion","created":1681277883,"sentence_id":0}';
r($aiTest->parseChatResponseTest($ernieNoResultResponse)) && p() && e('0');

// 步骤5：测试OpenAI模型缺少choices字段的响应
$aiTest->objectModel->modelConfig->type = 'openai-gpt35';
$openaiNoChoicesResponse = new stdClass();
$openaiNoChoicesResponse->result = 'success';
$openaiNoChoicesResponse->content = '{"id":"chatcmpl-123","object":"chat.completion","created":1677652288,"model":"gpt-3.5-turbo","usage":{"prompt_tokens":9,"completion_tokens":12,"total_tokens":21}}';
r($aiTest->parseChatResponseTest($openaiNoChoicesResponse)) && p() && e('0');