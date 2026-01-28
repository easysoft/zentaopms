#!/usr/bin/env php
<?php

/**

title=测试 aiModel::parseTextResponse();
timeout=0
cid=0

- 步骤1：正常情况
 -  @First response text
 - 属性1 @Second response text
- 步骤2：空choices数组 @0
- 步骤3：无choices字段 @0
- 步骤4：响应失败 @0
- 步骤5：无效JSON @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 4. 准备测试数据
$validResponse = new stdClass();
$validResponse->result = 'success';
$validResponse->content = json_encode(array(
    'choices' => array(
        (object) array('text' => 'First response text'),
        (object) array('text' => 'Second response text')
    )
));

$emptyChoicesResponse = new stdClass();
$emptyChoicesResponse->result = 'success';
$emptyChoicesResponse->content = json_encode(array('choices' => array()));

$noChoicesResponse = new stdClass();
$noChoicesResponse->result = 'success';
$noChoicesResponse->content = json_encode(array('data' => 'some other data'));

$failResponse = new stdClass();
$failResponse->result = 'fail';
$failResponse->message = 'API request failed';

$invalidJsonResponse = new stdClass();
$invalidJsonResponse->result = 'success';
$invalidJsonResponse->content = '{invalid json}';

// 5. 强制要求：必须包含至少5个测试步骤
r($aiTest->parseTextResponseTest($validResponse)) && p('0,1') && e('First response text,Second response text'); // 步骤1：正常情况
r($aiTest->parseTextResponseTest($emptyChoicesResponse)) && p() && e('0'); // 步骤2：空choices数组
r($aiTest->parseTextResponseTest($noChoicesResponse)) && p() && e('0'); // 步骤3：无choices字段
r($aiTest->parseTextResponseTest($failResponse)) && p() && e('0'); // 步骤4：响应失败
r($aiTest->parseTextResponseTest($invalidJsonResponse)) && p() && e('0'); // 步骤5：无效JSON