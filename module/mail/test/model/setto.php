#!/usr/bin/env php
<?php

/**

title=测试 mailModel->setTO();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('notify')->gen(0);

$mail = new mailTest();

$result1 = $mail->addQueueTest('', '');
$result2 = $mail->addQueueTest('user3', '测试提交队列', '测试发信内容');
$result3 = $mail->addQueueTest('user3,admin', '测试提交队列', '测试发信内容');
$result4 = $mail->addQueueTest('user3,admin', '测试提交队列', '测试发信内容', '', true);

r($result1) && p()               && e('没有数据提交'); //提交数据为空时
r($result2) && p('subject')      && e('测试提交队列'); //获取添加的邮件主题
r($result3) && p('toList')       && e('user3');        //获取收件人列表不包含自己
r($result4) && p('toList', ';')  && e('user3,admin');  //获取收件人列表包含自己

