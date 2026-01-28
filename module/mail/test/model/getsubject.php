#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getSubject();
timeout=0
cid=17011

- 获取关闭测试单时邮件主题 @1
- 获取创建文档时的邮件主题 @1
- 获取操作需求时的邮件主题 @1
- 获取操作任务时的邮件主题 @1
- 获取操作BUG时的邮件主题 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/* Create minimal required data for dependencies */
$dao = $GLOBALS['dao'];

/* Create product data (required for story and bug suffix) */
$dao->delete()->from(TABLE_PRODUCT)->where('id')->eq(1)->exec();
$dao->insert(TABLE_PRODUCT)
    ->data(array(
        'id' => 1,
        'name' => '正常产品1',
        'status' => 'normal'
    ))
    ->exec();

/* Create execution data (required for task suffix) */
$dao->delete()->from(TABLE_EXECUTION)->where('id')->eq(101)->exec();
$dao->insert(TABLE_EXECUTION)
    ->data(array(
        'id' => 101,
        'name' => '迭代1',
        'type' => 'sprint',
        'status' => 'wait'
    ))
    ->exec();

$mail = new mailModelTest();
$mail->objectModel->app->user->realname = '管理员';

$result1 = $mail->getSubjectTest('testtask', 1, '123', 'closed');
$result2 = $mail->getSubjectTest('doc', 1, 'test', 'releaseddoc');
$result3 = $mail->getSubjectTest('story', 1, 'test', 'created');
$result4 = $mail->getSubjectTest('task', 1, 'test', 'created');
$result5 = $mail->getSubjectTest('bug', 1, 'test', 'created');

r($result1 == 'admin关闭了测试单 #1:测试单1')   && p() && e('1'); //获取关闭测试单时邮件主题
r($result2 == 'admin发布了文档 #1:文档标题1')   && p() && e('1'); //获取创建文档时的邮件主题
r($result3 == 'REQUIREMENT #1 test - 正常产品1') && p() && e('1'); //获取操作需求时的邮件主题
r($result4 == 'TASK #1 test - 迭代1')            && p() && e('1'); //获取操作任务时的邮件主题
r($result5 == 'BUG #1 test - 正常产品1')         && p() && e('1'); //获取操作BUG时的邮件主题