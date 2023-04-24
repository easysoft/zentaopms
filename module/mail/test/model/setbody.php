#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

/**

title=测试 mailModel->setCC();
cid=1
pid=1

测试设置内容 >> test body

*/

$mail = new mailTest();

r($mail->setBodyTest('test body')) && p('Body') && e('test body'); //测试设置内容