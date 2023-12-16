#!/usr/bin/env php
<?php

/**

title=测试 mailModel->setSMTP();
cid=1
pid=1

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
