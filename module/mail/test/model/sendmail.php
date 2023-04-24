#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

/**

title=测试 mailModel->sendmail();
cid=1
pid=1

测试为dev4用户发送邮件通知 >> 0

*/

$mail = new mailTest();

r($mail->sendmailTest(48,48)) && p() && e('0'); //测试为dev4用户发送邮件通知