#!/usr/bin/env php
<?php

/**

title=测试 mailModel->setBody();
cid=0

- 测试设置内容属性Body @test body

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$mail = new mailTest();

r($mail->setBodyTest('test body')) && p('Body') && e('test body'); //测试设置内容
