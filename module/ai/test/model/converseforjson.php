#!/usr/bin/env php
<?php

/**

title=测试 aiModel::converseForJSON();
timeout=0
cid=0

- 执行aiTest模块的converseForJSONTest方法，参数是1, $validMessages, $validSchema  @0
- 执行aiTest模块的converseForJSONTest方法，参数是999, $validMessages, $validSchema  @0
- 执行aiTest模块的converseForJSONTest方法，参数是1, array  @0
- 执行aiTest模块的converseForJSONTest方法，参数是1, $validMessages, $invalidSchema  @0
- 执行aiTest模块的converseForJSONTest方法，参数是1, $validMessages, $validSchema, $options  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');

$aiTest = new aiTest();

// 准备测试数据
$validMessages = array(
    array('role' => 'user', 'content' => 'Generate a user profile with name and age')
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

$options = array('temperature' => 0.7, 'max_tokens' => 100);

r($aiTest->converseForJSONTest(1, $validMessages, $validSchema)) && p() && e('0');
r($aiTest->converseForJSONTest(999, $validMessages, $validSchema)) && p() && e('0');
r($aiTest->converseForJSONTest(1, array(), $validSchema)) && p() && e('0');
r($aiTest->converseForJSONTest(1, $validMessages, $invalidSchema)) && p() && e('0');
r($aiTest->converseForJSONTest(1, $validMessages, $validSchema, $options)) && p() && e('0');