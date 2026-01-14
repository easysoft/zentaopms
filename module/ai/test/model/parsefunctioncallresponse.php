#!/usr/bin/env php
<?php

/**

title=测试 aiModel::parseFunctionCallResponse();
timeout=0
cid=15059

- 执行aiTest模块的parseFunctionCallResponseTest方法，参数是$openaiResponse  @{"key": "value"}
- 执行aiTest模块的parseFunctionCallResponseTest方法，参数是$ernieResponse  @{"ernie_key": "ernie_value"}
- 执行aiTest模块的parseFunctionCallResponseTest方法，参数是$emptyResponse  @0
- 执行aiTest模块的parseFunctionCallResponseTest方法，参数是$failedResponse  @0
- 执行aiTest模块的parseFunctionCallResponseTest方法，参数是$noFunctionCallResponse  @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 4. 设置模型配置
$aiTest->objectModel->modelConfig = new stdClass();
$aiTest->objectModel->modelConfig->type = 'openai-gpt35';

// 5. 构造测试数据
$openaiResponseData = json_encode((object)array(
    'choices' => array(
        (object)array(
            'message' => (object)array(
                'function_call' => (object)array(
                    'arguments' => '{"key": "value"}'
                )
            )
        ),
        (object)array(
            'message' => (object)array(
                'function_call' => (object)array(
                    'arguments' => '{"key2": "value2"}'
                )
            )
        )
    )
));

$ernieResponseData = json_encode((object)array(
    'function_call' => (object)array(
        'arguments' => '{"ernie_key": "ernie_value"}'
    )
));

$noFunctionCallResponseData = json_encode((object)array(
    'choices' => array(
        (object)array(
            'message' => (object)array(
                'content' => 'normal text response'
            )
        )
    )
));

// 构造符合decodeResponse要求的响应对象
$openaiResponse = (object)array('result' => 'success', 'content' => $openaiResponseData);
$ernieResponse = (object)array('result' => 'success', 'content' => $ernieResponseData);
$emptyResponse = (object)array('result' => 'success', 'content' => '');
$invalidJsonResponse = (object)array('result' => 'success', 'content' => 'invalid json string');
$noFunctionCallResponse = (object)array('result' => 'success', 'content' => $noFunctionCallResponseData);
$failedResponse = (object)array('result' => 'fail', 'message' => 'API request failed');

// 6. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：测试OpenAI模型正常function call响应解析
r($aiTest->parseFunctionCallResponseTest($openaiResponse)) && p('0') && e('{"key": "value"}');

// 步骤2：测试Ernie模型正常function call响应解析
$aiTest->objectModel->modelConfig->type = 'baidu-ernie';
r($aiTest->parseFunctionCallResponseTest($ernieResponse)) && p('0') && e('{"ernie_key": "ernie_value"}');

// 步骤3：测试空content响应处理
r($aiTest->parseFunctionCallResponseTest($emptyResponse)) && p() && e('0');

// 步骤4：测试API失败响应处理
r($aiTest->parseFunctionCallResponseTest($failedResponse)) && p() && e('0');

// 步骤5：测试无function_call的响应处理
$aiTest->objectModel->modelConfig->type = 'openai-gpt35';
r(count($aiTest->parseFunctionCallResponseTest($noFunctionCallResponse))) && p() && e(0);