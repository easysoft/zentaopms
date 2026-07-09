#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiErrorHandling();
timeout=0
cid=0

- 测试空响应（falsy）>> no_response
- 测试带有error属性的响应 >> has_error
- 测试带有message字符串的响应 >> has_message_string
- 测试带有message对象的响应 >> has_message_object
- 测试无错误无消息的响应 >> no_error

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitlabTest = new gitlabModelTest();

$errorResponse = new stdclass();
$errorResponse->error = 'Something went wrong';

$msgStrResponse = new stdclass();
$msgStrResponse->message = 'Some error message';

$msgObjResponse = new stdclass();
$msgObj = new stdclass();
$msgObj->name = 'Name is invalid';
$msgObjResponse->message = $msgObj;

$noErrorResponse = new stdclass();
$noErrorResponse->id = 1;

r($gitlabTest->apiErrorHandlingTest(null)) && p() && e('no_response');
r($gitlabTest->apiErrorHandlingTest($errorResponse)) && p() && e('has_error');
r($gitlabTest->apiErrorHandlingTest($msgStrResponse)) && p() && e('has_message_string');
r($gitlabTest->apiErrorHandlingTest($msgObjResponse)) && p() && e('has_message_object');
r($gitlabTest->apiErrorHandlingTest($noErrorResponse)) && p() && e('no_error');
