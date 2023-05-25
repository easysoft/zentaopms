#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

/**

title=测试 mailModel->setCC();
cid=1
pid=1

测试设置主题 >> test subject

*/

$mail = new mailTest();

r($mail->setSubjectTest('test subject')) && p('Subject') && e('test subject'); //测试设置主题