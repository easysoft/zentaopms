#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getSubject();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$testtask = zdTable('testtask');
$testtask->createdBy->range('admin');
$testtask->createdDate->range('`' . date('Y-m-d H:i:s') . '`');
$testtask->gen(2);
zdTable('doc')->gen(2);
zdTable('docaction')->gen(0);
zdTable('task')->gen(2);
zdTable('story')->gen(2);
zdTable('bug')->gen(2);
zdTable('product')->gen(2);
$project = zdTable('project');
$project->id->range('101-105');
$project->name->range('1-5')->prefix('迭代');
$project->gen(2);

$mail = new mailTest();
$mail->objectModel->app->user->realname = '管理员';

$result1 = $mail->getSubjectTest('testtask', 1, '123', 'closed');
$result2 = $mail->getSubjectTest('doc', 1, 'test', 'created');
$result3 = $mail->getSubjectTest('story', 1, 'test', 'created');
$result4 = $mail->getSubjectTest('task', 1, 'test', 'created');
$result5 = $mail->getSubjectTest('bug', 1, 'test', 'created');

r($result1 == '管理员关闭了测试单 #1:测试单1') && p() && e('1'); //获取关闭测试单时邮件主题
r($result2 == '管理员创建了文档 #1:文档标题1') && p() && e('1'); //获取创建文档时的邮件主题
r($result3 == 'STORY #1 test - 正常产品1')     && p() && e('1'); //获取操作需求时的邮件主题
r($result4 == 'TASK #1 test - 迭代1')          && p() && e('1'); //获取操作任务时的邮件主题
r($result5 == 'BUG #1 test - 正常产品1')       && p() && e('1'); //获取操作BUG时的邮件主题
