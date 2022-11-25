#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/mail.class.php';
su('admin');

/**

title=测试 mailModel->addQueue();
cid=1
pid=1

提交数据为空时 >> 没有数据提交
获取添加的邮件主题 >> 测试提交队列
获取收件人列表不包含自己 >> user3
获取收件人列表包含自己 >> user3,admin

*/

$mail = new mailTest();

$result1 = $mail->addQueueTest('','');
$result2 = $mail->addQueueTest('user3', '测试提交队列', '测试发信内容');
$result3 = $mail->addQueueTest('user3,admin', '测试提交队列', '测试发信内容');
$result4 = $mail->addQueueTest('user3,admin', '测试提交队列', '测试发信内容', '', true);

r($result1) && p()          && e('没有数据提交'); //提交数据为空时
r($result2) && p('subject') && e('测试提交队列'); //获取添加的邮件主题
r($result3) && p('toList')  && e('user3');        //获取收件人列表不包含自己
r($result4) && p('toList')  && e('user3,admin');  //获取收件人列表包含自己