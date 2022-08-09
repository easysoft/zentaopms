#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/mail.class.php';
su('admin');

/**

title=测试 mailModel->setCC();
cid=1
pid=1

获取MTA主机 >> localhost
获取MTAdebug设置 >> 0

*/

$mail = new mailTest();

r($mail->setMTATest()) && p('Host')      && e('localhost'); //获取MTA主机
r($mail->setMTATest()) && p('SMTPDebug') && e('0');         //获取MTAdebug设置