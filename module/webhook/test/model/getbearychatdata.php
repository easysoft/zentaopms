#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getBearychatData();
timeout=0
cid=1

- 测试传入正确数据属性markdown @true
- 测试不传text属性markdown @true
- 测试不传mobile属性markdown @true
- 测试不传email属性markdown @true
- 测试不传objectType属性markdown @true
- 测试不传objectID属性markdown @true

*/

$webhook = new webhookTest();

$text    = array();
$text[0] = 'denghongtao';
$text[1] = '';

$mobile     = array();
$mobile[0]     = '12345';
$mobile[1]     = '';

$email    = array();
$email[0] = '123456@outlook.com';
$email[1] = '';

$objectType = array();
$objectType[0] = 'product';
$objectType[1] = '';

$objectID    = array();
$objectID[0] = '1';
$objectID[1] = 0;

$result1 = $webhook->getBearychatDataTest($text[0], $mobile[0], $email[0], $objectType[0], $objectID[0]);
$result2 = $webhook->getBearychatDataTest($text[1], $mobile[0], $email[0], $objectType[0], $objectID[0]);
$result3 = $webhook->getBearychatDataTest($text[0], $mobile[1], $email[0], $objectType[0], $objectID[0]);
$result4 = $webhook->getBearychatDataTest($text[0], $mobile[0], $email[1], $objectType[0], $objectID[0]);
$result5 = $webhook->getBearychatDataTest($text[0], $mobile[0], $email[0], $objectType[1], $objectID[0]);
$result6 = $webhook->getBearychatDataTest($text[0], $mobile[0], $email[0], $objectType[0], $objectID[1]);

r($result1) && p('markdown') && e('true'); //测试传入正确数据
r($result2) && p('markdown') && e('true'); //测试不传text
r($result3) && p('markdown') && e('true'); //测试不传mobile
r($result4) && p('markdown') && e('true'); //测试不传email
r($result5) && p('markdown') && e('true'); //测试不传objectType
r($result6) && p('markdown') && e('true'); //测试不传objectID