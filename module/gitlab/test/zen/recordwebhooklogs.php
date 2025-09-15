#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::recordWebhookLogs();
timeout=0
cid=0

- 步骤1：正常情况 - 检查返回不为false @1
- 步骤2：空字符串输入 - 检查返回不为false @1
- 步骤3：复杂对象 - 检查返回不为false @1
- 步骤4：特殊字符 - 检查返回不为false @1
- 步骤5：大数据量 - 检查返回不为false @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$gitlabTest = new gitlabTest();

// 4. 准备测试数据
$normalInput = '{"object_kind":"issue","object_attributes":{"id":1,"title":"Test Issue"}}';
$normalResult = new stdclass;
$normalResult->issue = new stdclass;
$normalResult->issue->objectType = 'bug';
$normalResult->object = new stdclass;
$normalResult->object->id = 1;
$normalResult->object->title = 'Test Bug';

$emptyInput = '';
$emptyResult = new stdclass;
$emptyResult->issue = new stdclass;
$emptyResult->issue->objectType = 'task';
$emptyResult->object = new stdclass;
$emptyResult->object->id = 0;

$complexInput = '{"object_kind":"issue","object_attributes":{"id":123,"title":"Complex Issue","description":"Test description with\nmultiple lines","labels":[{"title":"bug/456"}]}}';
$complexResult = new stdclass;
$complexResult->issue = new stdclass;
$complexResult->issue->objectType = 'story';
$complexResult->object = new stdclass;
$complexResult->object->id = 123;
$complexResult->object->title = 'Complex Story';
$complexResult->object->description = 'Complex description';

$specialCharsInput = '{"title":"Test with \"quotes\" and \\backslashes\\ and 中文"}';
$specialCharsResult = new stdclass;
$specialCharsResult->issue = new stdclass;
$specialCharsResult->issue->objectType = 'bug';
$specialCharsResult->object = new stdclass;
$specialCharsResult->object->title = 'Special chars test';

$largeDataInput = str_repeat('{"key":"value","data":"' . str_repeat('x', 100) . '"}', 3);
$largeDataResult = new stdclass;
$largeDataResult->issue = new stdclass;
$largeDataResult->issue->objectType = 'task';
$largeDataResult->object = new stdclass;
$largeDataResult->object->data = 'large data content';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $gitlabTest->recordWebhookLogsTest($normalInput, $normalResult);
$result2 = $gitlabTest->recordWebhookLogsTest($emptyInput, $emptyResult);
$result3 = $gitlabTest->recordWebhookLogsTest($complexInput, $complexResult);
$result4 = $gitlabTest->recordWebhookLogsTest($specialCharsInput, $specialCharsResult);
$result5 = $gitlabTest->recordWebhookLogsTest($largeDataInput, $largeDataResult);

r($result1 !== false) && p() && e(1); // 步骤1：正常情况 - 检查返回不为false
r($result2 !== false) && p() && e(1); // 步骤2：空字符串输入 - 检查返回不为false
r($result3 !== false) && p() && e(1); // 步骤3：复杂对象 - 检查返回不为false
r($result4 !== false) && p() && e(1); // 步骤4：特殊字符 - 检查返回不为false
r($result5 !== false) && p() && e(1); // 步骤5：大数据量 - 检查返回不为false