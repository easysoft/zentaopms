#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getDingdingData();
timeout=0
cid=1

- 打印出msgtype第markdown条的title属性 @ceshi
- 测试不传text的情况第markdown条的title属性 @ceshi
- 测试不传mobile的情况第markdown条的title属性 @ceshi
- 测试不传mobile的情况第markdown条的title属性 @~~

*/

$webhook = new webhookTest();

$title = array();
$title[0] = 'ceshi';
$title[1] = '';

$text = array();
$text[0] = '文本信息';
$text[1] = '';

$mobile = array();
$mobile[0] = '123456';
$mobile[1] = '';

r($webhook->getDingdingDataTest($title[0], $text[0], $mobile[0])) && p('markdown:title') && e('ceshi'); //打印出msgtype
r($webhook->getDingdingDataTest($title[0], $text[1], $mobile[0])) && p('markdown:title') && e('ceshi'); //测试不传text的情况
r($webhook->getDingdingDataTest($title[0], $text[0], $mobile[1])) && p('markdown:title') && e('ceshi'); //测试不传mobile的情况
r($webhook->getDingdingDataTest($title[1], $text[0], $mobile[0])) && p('markdown:title') && e('~~');    //测试不传mobile的情况