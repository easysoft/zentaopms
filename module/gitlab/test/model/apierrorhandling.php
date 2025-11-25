#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiErrorHandling();
timeout=0
cid=16596

- 步骤1：error字段错误处理 @403 Forbidden
- 步骤2：字符串message错误处理 @保存失败，群组URL路径已经被使用。
- 步骤3：对象message数组字段错误处理第name条的0属性 @已经被使用
- 步骤4：对象message字符串字段错误处理第password条的0属性 @密码太短（最少8个字符）
- 步骤5：多字段错误处理 - 第一个name错误第name条的0属性 @GitLab项目已存在
- 步骤6：空字段边界情况处理第valid_field条的0属性 @GitLab项目已存在
- 步骤7：空响应异常情况处理 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

zenData('pipeline')->gen(5);

su('admin');

$gitlab = new gitlabTest();

// 测试步骤1：响应包含error字段的错误处理
$response1 = new stdclass();
$response1->error = '403 Forbidden';

// 测试步骤2：响应包含字符串message的错误处理
$response2 = new stdclass();
$response2->message = 'Failed to save group {:path=>["已经被使用"]}';

// 测试步骤3：响应包含对象message且字段为数组的错误处理
$response3 = new stdclass();
$response3->message = new stdclass();
$response3->message->name = array('已经被使用');

// 测试步骤4：响应包含对象message且字段为字符串的错误处理
$response4 = new stdclass();
$response4->message = new stdclass();
$response4->message->password = 'is too short (minimum is 8 characters)';

// 测试步骤5：响应包含多个字段错误的复杂错误处理
$response5 = new stdclass();
$response5->message = new stdclass();
$response5->message->name = array('has already been taken', 'is invalid');
$response5->message->path = 'admin is a reserved name';

// 测试步骤6：响应包含空message的边界情况处理
$response6 = new stdclass();
$response6->message = new stdclass();
$response6->message->empty_field = array();
$response6->message->valid_field = 'has already been taken';

// 测试步骤7：响应为空对象的异常情况处理
$response7 = new stdclass();

r($gitlab->apiErrorHandlingTest($response1)) && p('0') && e('403 Forbidden'); // 步骤1：error字段错误处理
r($gitlab->apiErrorHandlingTest($response2)) && p('0') && e('保存失败，群组URL路径已经被使用。'); // 步骤2：字符串message错误处理
r($gitlab->apiErrorHandlingTest($response3)) && p('name:0') && e('已经被使用'); // 步骤3：对象message数组字段错误处理
r($gitlab->apiErrorHandlingTest($response4)) && p('password:0') && e('密码太短（最少8个字符）'); // 步骤4：对象message字符串字段错误处理
r($gitlab->apiErrorHandlingTest($response5)) && p('name:0') && e('GitLab项目已存在'); // 步骤5：多字段错误处理 - 第一个name错误
r($gitlab->apiErrorHandlingTest($response6)) && p('valid_field:0') && e('GitLab项目已存在'); // 步骤6：空字段边界情况处理
r($gitlab->apiErrorHandlingTest($response7)) && p('0') && e('0'); // 步骤7：空响应异常情况处理