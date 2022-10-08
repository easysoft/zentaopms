#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/mail.class.php';
su('admin');

/**

title=测试 mailModel->getQueueById();
cid=1
pid=1

获取id为1的邮件主题 >> 主题1
获取id为10的收件人 >> top10
获取id不存在的邮件信息 >> 0

*/

$mail = new mailTest();

$queueID = array(1,10,0);

r($mail->getQueueByIdTest($queueID[0])) && p('subject') && e('主题1'); //获取id为1的邮件主题
r($mail->getQueueByIdTest($queueID[1])) && p('toList')  && e('top10'); //获取id为10的收件人
r($mail->getQueueByIdTest($queueID[2])) && p()          && e('0');     //获取id不存在的邮件信息