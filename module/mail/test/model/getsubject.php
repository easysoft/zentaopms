#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getSubject();
cid=1
pid=1

获取关闭测试单时邮件主题 >> admin关闭了测试单 #1:测试单1
获取创建文档时的邮件主题 >> admin创建了文档 #1:文档标题901
获取操作需求时的邮件主题 >> STORY #1 test - 正常产品1
获取操作任务时的邮件主题 >> TASK #1 test - 迭代1
获取操作BUG时的邮件主题 >> BUG #1 test - 正常产品1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$mail = new mailTest();

$result1 = $mail->getSubjectTest('testtask', 1, '123', 'closed');
$result2 = $mail->getSubjectTest('doc', 1, 'test', 'created');
$result3 = $mail->getSubjectTest('story', 1, 'test', 'created');
$result4 = $mail->getSubjectTest('task', 1, 'test', 'created');
$result5 = $mail->getSubjectTest('bug', 1, 'test', 'created');

r($result1) && p() && e('admin关闭了测试单 #1:测试单1');   //获取关闭测试单时邮件主题
r($result2) && p() && e('admin创建了文档 #1:文档标题901'); //获取创建文档时的邮件主题
r($result3) && p() && e('STORY #1 test - 正常产品1');      //获取操作需求时的邮件主题
r($result4) && p() && e('TASK #1 test - 迭代1');           //获取操作任务时的邮件主题
r($result5) && p() && e('BUG #1 test - 正常产品1');        //获取操作BUG时的邮件主题
