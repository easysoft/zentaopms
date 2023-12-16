#!/usr/bin/env php
<?php

/**

title=测试 mailModel->convertCharset();
timeout=0
cid=0

- 不传入任何参数 @0
- 都是utf8格式 @测试
- GBk转utf8格式。 @测试

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$mailModel = $tester->loadModel('mail');
$mailModel->config->charset = 'utf-8';
$mailModel->config->mail->smtp->charset = 'utf-8';

$utf8String = '测试';
$gbkString  = iconv('utf-8', 'gbk', '测试');

r($mailModel->convertCharset(''))          && p()   && e('0');    //不传入任何参数
r($mailModel->convertCharset($utf8String)) && p()   && e('测试'); //都是utf8格式

$mailModel->config->charset = 'gbk';
r($mailModel->convertCharset($gbkString))  && p()   && e('测试'); //GBk转utf8格式。