#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getWeixinData();
cid=1
pid=1

测试正常传入的情况 >> 文本选择
测试不传title >> 文本选择
测试不传mobile >> 文本选择

*/

$webhook = new webhookTest();

$title  = array();
$title[0]  = '名称';
$title[1]  = '';

$text   = array();
$text[0]   = '文本选择';
$text[1]   = '';

$mobile = array();
$mobile[0] = '123456';
$mobile[1] = '';

$result1 = $webhook->getWeixinDataTest($title[0], $text[0], $mobile[0]);
$result2 = $webhook->getWeixinDataTest($title[1], $text[0], $mobile[0]);
$result3 = $webhook->getWeixinDataTest($title[0], $text[1], $mobile[0]);
$result4 = $webhook->getWeixinDataTest($title[0], $text[0], $mobile[1]);

r($result1) && p('markdown:content') && e('文本选择'); //测试正常传入的情况
r($result2) && p('markdown:content') && e('文本选择'); //测试不传title
r($result3) && p('markdown:content') && e('');         //测试不传text
r($result4) && p('markdown:content') && e('文本选择'); //测试不传mobile