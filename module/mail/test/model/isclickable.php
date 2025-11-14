#!/usr/bin/env php
<?php

/**

title=测试 mailModel::isClickable();
timeout=0
cid=17012

- 步骤1：空方法名参数测试 @0
- 步骤2：delete方法权限测试（管理员） @1
- 步骤3：resend方法且状态为wait的测试 @0
- 步骤4：resend方法且状态为fail的测试 @1
- 步骤5：无效方法名参数测试 @0
- 步骤6：普通用户delete权限测试 @1
- 步骤7：普通用户resend权限测试 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

$table = zenData('notify');
$table->id->range('1-10');
$table->objectType->range('mail');
$table->objectID->range('1-10');
$table->status->range('wait{5},fail{3},sent{2}');
$table->toList->range('admin@zentao.com,user@zentao.com');
$table->subject->range('测试邮件{10}');
$table->gen(10);

su('admin');

$mailTest = new mailTest();

$notify = $mailTest->getQueueByIdTest(1);

r($mailTest->isClickableTest($notify, ''))         && p() && e('0'); // 步骤1：空方法名参数测试
r($mailTest->isClickableTest($notify, 'delete'))   && p() && e('1'); // 步骤2：delete方法权限测试（管理员）
r($mailTest->isClickableTest($notify, 'resend'))   && p() && e('0'); // 步骤3：resend方法且状态为wait的测试

$notify->status = 'fail';
r($mailTest->isClickableTest($notify, 'resend'))   && p() && e('1'); // 步骤4：resend方法且状态为fail的测试
r($mailTest->isClickableTest($notify, 'invalid'))  && p() && e('0'); // 步骤5：无效方法名参数测试

su('user');
r($mailTest->isClickableTest($notify, 'delete'))   && p() && e('1'); // 步骤6：普通用户delete权限测试
r($mailTest->isClickableTest($notify, 'resend'))   && p() && e('1'); // 步骤7：普通用户resend权限测试