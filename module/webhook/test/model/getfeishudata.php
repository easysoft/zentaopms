#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 webhookModel->getFeishuData();
timeout=0
cid=19697

- 测试正常传入的情况第title条的content属性 @test1
- 测试不传title的情况第title条的content属性 @~~
- 测试不传text的情况第title条的content属性 @test1
- 查看tag第title条的tag属性 @plain_text
- 查看template属性template @blue

*/

$webhook = new webhookModelTest();

$title = array();
$title[0] = 'test1';
$title[1] = '';

$text  = array();
$text[0] = 'test2';
$text[1] = '';

$result1 = $webhook->getFeishuDataTest($title[0], $text[0]);
$result2 = $webhook->getFeishuDataTest($title[1], $text[0]);
$result3 = $webhook->getFeishuDataTest($title[0], $text[1]);

r($result1->card['header']) && p('title:content') && e('test1'); //测试正常传入的情况
r($result2->card['header']) && p('title:content') && e('~~');    //测试不传title的情况
r($result3->card['header']) && p('title:content') && e('test1'); //测试不传text的情况
r($result3->card['header']) && p('title:tag')     && e('plain_text'); //查看tag
r($result3->card['header']) && p('template')      && e('blue'); //查看template