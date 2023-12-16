#!/usr/bin/env php
<?php

/**

title=测试 mailModel->setCC();
timeout=0
cid=0

- 测试设置主题属性Subject @test subject

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$mail = new mailTest();

r($mail->setSubjectTest('test subject')) && p('Subject') && e('test subject'); //测试设置主题