#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getViewLink();
timeout=0
cid=1

- 打印出了get链接，我是通过./执行文件，所以打印的是./文件名和传入的参数，通过页面调用返回的则是url @-getviewlink.php?m=product&f=view&id=1
- 同样返回调用方法的url @-getviewlink.php?m=story&f=view&id=2
- 当不传入参数时 @-getviewlink.php?m=&f=view&id=0

*/

$webhook = new webhookTest();

$type = array();
$type[0] = 'product';
$type[1] = 'story';
$type[2] = '';

$ID   = array();
$ID[0]   = '1';
$ID[1]   = '2';
$ID[2]   = 0;

$result1 = $webhook->getViewLinkTest($type[0], $ID[0]);
$result2 = $webhook->getViewLinkTest($type[1], $ID[1]);
$result3 = $webhook->getViewLinkTest($type[2], $ID[2]);

r($result1) && p() && e('-getviewlink.php?m=product&f=view&id=1'); //打印出了get链接，我是通过./执行文件，所以打印的是./文件名和传入的参数，通过页面调用返回的则是url
r($result2) && p() && e('-getviewlink.php?m=story&f=view&id=2');   //同样返回调用方法的url
r($result3) && p() && e('-getviewlink.php?m=&f=view&id=0');        //当不传入参数时