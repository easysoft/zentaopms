#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getDingdingData();
cid=1
pid=1

打印出msgtype >> 文本信息 @123456
测试不传text的情况 >> @123456
测试不传mobile的情况 >> 文本信息
测试不传mobile的情况 >> 文本信息 @123456

*/

$webhook = new webhookTest();

$title = array();
$title[0] = 'denghongtao';
$title[1] = '';

$text = array();
$text[0] = '文本信息';
$text[1] = '';

$mobile = array();
$mobile[0] = '123456';
$mobile[1] = '';

r($webhook->getDingdingDataTest($title[0], $text[0], $mobile[0])) && p('markdown:text') && e('文本信息 @123456'); //打印出msgtype
r($webhook->getDingdingDataTest($title[0], $text[1], $mobile[0])) && p('markdown:text') && e('@123456');          //测试不传text的情况
r($webhook->getDingdingDataTest($title[0], $text[0], $mobile[1])) && p('markdown:text') && e('文本信息');         //测试不传mobile的情况
r($webhook->getDingdingDataTest($title[1], $text[0], $mobile[0])) && p('markdown:text') && e('文本信息 @123456'); //测试不传mobile的情况