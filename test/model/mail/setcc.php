#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/mail.class.php';
su('admin');

/**

title=测试 mailModel->setCC();
cid=1
pid=1

*/

$mail = new mailTest();
$mail->setMTATest();
a($mail->setSubjectTest('123123'));
a($mail->setMTATest());
die;
r($mail->setCCTest()) && p() && e();
