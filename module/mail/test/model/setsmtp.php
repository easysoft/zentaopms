#!/usr/bin/env php
<?php

/**

title=测试 mailModel->setSMTP();
cid=0

- 获取SMTP主机
 - 属性Host @localhost
 - 属性SMTPDebug @0
 - 属性CharSet @utf-8
- 修改host，检查SMTP主机。属性Host @127.0.0.1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$mailModel = $tester->loadModel('mail');

$mailModel->setSMTP();
r($mailModel->mta) && p('Host,SMTPDebug,CharSet') && e('localhost,0,utf-8'); //获取SMTP主机

$mailModel->config->mail->smtp->host = '127.0.0.1';
$mailModel->setSMTP();
r($mailModel->mta) && p('Host') && e('127.0.0.1'); //修改host，检查SMTP主机。
