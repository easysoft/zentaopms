#!/usr/bin/env php
<?php

/**

title=测试 mailModel->isClickable();
cid=0

- 不传入method参数 @0
- 传入method=delete参数 @1
- 传入method=resend参数 @0
- 传入method=resend参数, item的status字段为fail @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('notify')->gen(2);

global $tester;
$mailModel = $tester->loadModel('mail');
$mailModel->app->user->admin = true;

$notify = $mailModel->getQueueById(1);

r($mailModel->isClickable($notify, ''))       && p() && e('0'); //不传入method参数
r($mailModel->isClickable($notify, 'delete')) && p() && e('1'); //传入method=delete参数
r($mailModel->isClickable($notify, 'resend')) && p() && e('0'); //传入method=resend参数

$notify->status = 'fail';
r($mailModel->isClickable($notify, 'resend')) && p() && e('1'); //传入method=resend参数, item的status字段为fail
