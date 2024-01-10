#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::apiErrorHandling();
timeout=0
cid=1

- 错误处理1 @错误1
- 错误处理2 @保存失败，群组URL路径已经被使用。
- 错误处理3第name条的0属性 @已经被使用

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$response1 = new stdclass();
$response1->error = '错误1';

$response2 = new stdclass();
$response2->message = 'Failed to save group {:path=>["已经被使用"]}';

$response3 = new stdclass();
$response3->message = new stdclass();
$response3->message->name = array('已经被使用');

r($gitlab->apiErrorHandlingTest($response1)) && p('0')      && e('错误1'); //错误处理1
r($gitlab->apiErrorHandlingTest($response2)) && p('0')      && e('保存失败，群组URL路径已经被使用。'); //错误处理2
r($gitlab->apiErrorHandlingTest($response3)) && p('name:0') && e('已经被使用'); //错误处理3
