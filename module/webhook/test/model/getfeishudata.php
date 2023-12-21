#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getFeishuData();
timeout=0
cid=1

- 测试正常传入的情况第title条的content属性 @denghongtao
- 测试不传title的情况第title条的content属性 @~~
- 测试不传text的情况第title条的content属性 @denghongtao

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

r($result1->card['header']) && p('title:content') && e('denghongtao'); //测试正常传入的情况
r($result2->card['header']) && p('title:content') && e('~~');          //测试不传title的情况
r($result3->card['header']) && p('title:content') && e('denghongtao'); //测试不传text的情况