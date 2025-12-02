#!/usr/bin/env php
<?php

/**

title=测试 aiModel::converseTwiceForJSON();
timeout=0
cid=15007

- 执行aiTest模块的converseTwiceForJSONTest方法，参数是1, $validMessages, $validSchema, $validOptions  @0
- 执行aiTest模块的converseTwiceForJSONTest方法，参数是0, $validMessages, $validSchema, $validOptions  @0
- 执行aiTest模块的converseTwiceForJSONTest方法，参数是1, array  @0
- 执行aiTest模块的converseTwiceForJSONTest方法，参数是1, $validMessages, null, $validOptions  @0
- 执行aiTest模块的converseTwiceForJSONTest方法，参数是999, $validMessages, $validSchema, $validOptions  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

global $tester;
$tester->dao->exec("DELETE FROM " . TABLE_AI_MODEL . " WHERE 1=1");
$tester->dao->exec("INSERT INTO " . TABLE_AI_MODEL . " (`id`, `type`, `vendor`, `credentials`, `proxy`, `name`, `desc`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `enabled`, `deleted`) VALUES
    (1, 'openai-gpt35', 'openai', '{\"key\":\"test-key\"}', '{}', 'OpenAI GPT-3.5', 'GPT-3.5 Turbo', 'admin', '2023-01-01 10:00:00', '', NULL, '0', '0'),
    (2, 'openai-gpt4', 'openai', '', '', 'OpenAI GPT-4', 'GPT-4 Model', 'admin', '2023-01-02 10:00:00', '', NULL, '0', '0'),
    (999, 'invalid-model', 'invalid', '', '', 'Invalid Model', 'Invalid Model', 'admin', '2023-01-03 10:00:00', '', NULL, '0', '1')");

su('admin');

$aiTest = new aiTest();

$validMessages = array(
    (object)array('role' => 'user', 'content' => 'Please analyze this data and provide structured output')
);

$validSchema = (object)array(
    'type' => 'object',
    'properties' => (object)array(
        'result' => (object)array('type' => 'string'),
        'status' => (object)array('type' => 'string')
    ),
    'required' => array('result', 'status')
);

$validOptions = array('temperature' => 0.7, 'max_tokens' => 100);

r($aiTest->converseTwiceForJSONTest(1, $validMessages, $validSchema, $validOptions)) && p() && e('0');
r($aiTest->converseTwiceForJSONTest(0, $validMessages, $validSchema, $validOptions)) && p() && e('0');
r($aiTest->converseTwiceForJSONTest(1, array(), $validSchema, $validOptions)) && p() && e('0');
r($aiTest->converseTwiceForJSONTest(1, $validMessages, null, $validOptions)) && p() && e('0');
r($aiTest->converseTwiceForJSONTest(999, $validMessages, $validSchema, $validOptions)) && p() && e('0');