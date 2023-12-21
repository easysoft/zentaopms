#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getWeixinData();
timeout=0
cid=1

- 测试正常传入的情况第text条的content属性 @文本选择
- 测试不传title第text条的content属性 @文本选择
- 测试不传text第text条的content属性 @
- 测试不传mobile第markdown条的content属性 @文本选择

*/

$webhook = new webhookTest();

$text   = array();
$text[0]   = '文本选择';
$text[1]   = '';

$mobile = array();
$mobile[0] = '123456';
$mobile[1] = '';

$result1 = $webhook->getWeixinDataTest($text[0], $mobile[0]);
$result2 = $webhook->getWeixinDataTest($text[0], $mobile[0]);
$result3 = $webhook->getWeixinDataTest($text[1], $mobile[0]);
$result4 = $webhook->getWeixinDataTest($text[0], $mobile[1]);

r($result1) && p('text:content')     && e('文本选择'); //测试正常传入的情况
r($result2) && p('text:content')     && e('文本选择'); //测试不传title
r($result3) && p('text:content')     && e('~`');       //测试不传text
r($result4) && p('markdown:content') && e('文本选择'); //测试不传mobile
