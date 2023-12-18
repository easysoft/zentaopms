#!/usr/bin/env php
<?php

/**

title=测试 mailModel->send();
cid=0

 >> 0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$mail = new mailTest();

/* If you don't set SMTP Please input you SMTP setting. */
$mailConfig = new stdclass();
$mailConfig->smtp = new stdclass();

$mailConfig->turnon         = 1;
$mailConfig->mta            = 'smtp';
$mailConfig->async          = 0;
$mailConfig->fromAddress    = '';
$mailConfig->fromName       = '';
$mailConfig->domain         = '';
$mailConfig->smtp->host     = '';
$mailConfig->smtp->port     = '';
$mailConfig->smtp->auth     = '1';
$mailConfig->smtp->username = '';
$mailConfig->smtp->password = '';
$mailConfig->smtp->secure   = '';
$mailConfig->smtp->debug    = 1;
$mailConfig->smtp->charset  = 'utf-8';

global $tester;
if(!isset($tester->config->mail->turnon)) $tester->loadModel('setting')->setItems('system.mail', $mailConfig);

$result1 = $mail->sendTest('admin','test','test','',true);

r($result1) && p() && e('0'); //
