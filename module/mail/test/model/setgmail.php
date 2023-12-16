#!/usr/bin/env php
<?php

/**

title=测试 mailModel->setGMail();
cid=0

- 获取Gmail主机
 - 属性Mailer @smtp
 - 属性Host @smtp.gmail.com
 - 属性Port @465
 - 属性SMTPSecure @ssl
 - 属性Username @admin
 - 属性Password @123456

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
