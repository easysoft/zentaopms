#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getFeishuData();
cid=1
pid=1

测试正常传入的情况 >> wenbenxiaoxiang
测试不传title的情况 >> wenbenxiaoxiang

*/

$webhook = new webhookTest();

$title = array();
$title[0] = 'denghongtao';
$title[1] = '';

$text  = array();
$text[0] = 'wenbenxiaoxiang';
$text[1] = '';

$result1 = $webhook->getFeishuDataTest($title[0], $text[0]);
$result2 = $webhook->getFeishuDataTest($title[1], $text[0]);
$result3 = $webhook->getFeishuDataTest($title[0], $text[1]);

r($result1) && p('content:text') && e('wenbenxiaoxiang'); //测试正常传入的情况
r($result2) && p('content:text') && e('wenbenxiaoxiang'); //测试不传title的情况
r($result3) && p('content:text') && e('');                //测试不传text的情况