#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getQueueById();
timeout=0
cid=1

- 获取id为1的邮件主题属性subject @主题1
- 获取id为10的收件人属性toList @top10
- 获取id=0的邮件信息 @0
- 获取id不存在的邮件信息 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

zdTable('notify')->gen(10);

$mail = new mailTest();

$queueID = array(1, 10, 0, 15);

r($mail->getQueueByIdTest($queueID[0])) && p('subject') && e('主题1'); //获取id为1的邮件主题
r($mail->getQueueByIdTest($queueID[1])) && p('toList')  && e('top10'); //获取id为10的收件人
r($mail->getQueueByIdTest($queueID[2])) && p()          && e('0');     //获取id=0的邮件信息
r($mail->getQueueByIdTest($queueID[3])) && p()          && e('0');     //获取id不存在的邮件信息
