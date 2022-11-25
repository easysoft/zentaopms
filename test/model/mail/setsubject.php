#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/mail.class.php';
su('admin');

/**

title=测试 mailModel->setCC();
cid=1
pid=1

测试设置主题 >> test subject

*/

$mail = new mailTest();

r($mail->setSubjectTest('test subject')) && p('Subject') && e('test subject'); //测试设置主题