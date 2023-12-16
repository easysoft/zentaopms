#!/usr/bin/env php
<?php

/**

title=测试 mailModel->setMTA();
cid=1
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$mail = new mailTest();

r($mail->setMTATest()) && p('Host')      && e('localhost'); //获取MTA主机
r($mail->setMTATest()) && p('SMTPDebug') && e('0');         //获取MTAdebug设置
