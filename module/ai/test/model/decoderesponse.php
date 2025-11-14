#!/usr/bin/env php
<?php

/**

title=测试 aiModel::decodeResponse();
timeout=0
cid=15015

- 执行aiTest模块的decodeResponseTest方法，参数是$validResponse 属性message @Hello World
- 执行aiTest模块的decodeResponseTest方法，参数是$failResponse  @0
- 执行aiTest模块的decodeResponseTest方法，参数是$invalidJsonResponse  @0
- 执行aiTest模块的decodeResponseTest方法，参数是$emptyResponse  @0
- 执行aiTest模块的decodeResponseTest方法，参数是$complexResponse 属性model @gpt-3.5-turbo

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$aiTest = new aiTest();

// 4. 测试步骤
// 步骤1：正常JSON响应解码
$validResponse = new stdClass();
$validResponse->result = 'success';
$validResponse->content = '{"message":"Hello World"}';
r($aiTest->decodeResponseTest($validResponse)) && p('message') && e('Hello World');

// 步骤2：响应结果为fail时的处理
$failResponse = new stdClass();
$failResponse->result = 'fail';
$failResponse->message = 'API request failed';
r($aiTest->decodeResponseTest($failResponse)) && p() && e('0');

// 步骤3：无效JSON格式响应的处理
$invalidJsonResponse = new stdClass();
$invalidJsonResponse->result = 'success';
$invalidJsonResponse->content = '{"invalid": json syntax';
r($aiTest->decodeResponseTest($invalidJsonResponse)) && p() && e('0');

// 步骤4：空响应内容的处理
$emptyResponse = new stdClass();
$emptyResponse->result = 'success';
$emptyResponse->content = '';
r($aiTest->decodeResponseTest($emptyResponse)) && p() && e('0');

// 步骤5：复杂嵌套JSON结构的解码
$complexResponse = new stdClass();
$complexResponse->result = 'success';
$complexResponse->content = '{"model":"gpt-3.5-turbo","status":"completed"}';
r($aiTest->decodeResponseTest($complexResponse)) && p('model') && e('gpt-3.5-turbo');