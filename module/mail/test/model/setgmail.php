#!/usr/bin/env php
<?php

/**

title=测试 mailModel->setGMail();
cid=1
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$mailModel = $tester->loadModel('mail');
$mailModel->config->mail->gmail = new stdclass();
$mailModel->config->mail->gmail->debug    = '0';
$mailModel->config->mail->gmail->username = 'admin';
$mailModel->config->mail->gmail->password = '123456';

$mailModel->setGMail();
r($mailModel->mta) && p('Mailer,Host,Port,SMTPSecure,Username,Password') && e('smtp,smtp.gmail.com,465,ssl,admin,123456'); //获取Gmail主机
