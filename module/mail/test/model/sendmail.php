#!/usr/bin/env php
<?php

/**

title=测试 mailModel::sendmail();
timeout=0
cid=0

- 测试步骤1：空参数输入情况属性processed @1
- 测试步骤2：只传入actionID无objectID属性processed @1
- 测试步骤3：只传入objectID无actionID属性processed @1
- 测试步骤4：传入有效的objectID和actionID（捕获异常）
 - 属性processed @0
 - 属性objectID @1
 - 属性actionID @1
- 测试步骤5：测试正常邮件发送流程（捕获异常）属性processed @0
- 测试步骤6：测试其他类型邮件发送属性processed @1
- 测试步骤7：测试更多类型邮件发送属性processed @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

$action = zenData('action');
$action->id->range('1-10');
$action->objectType->range('story{2},task{2},bug{2},mr{2},ticket{2}');
$action->objectID->range('1-10');
$action->actor->range('admin,user1,user2');
$action->action->range('opened,created,changed,closed');
$action->date->range('`2024-01-01 00:00:00`,`2024-01-02 00:00:00`,`2024-01-03 00:00:00`');
$action->gen(10);

zenData('story')->gen(5);
zenData('product')->gen(3);
zenData('project')->gen(3);
zenData('task')->gen(5);
zenData('bug')->gen(5);
zenData('user')->gen(10);

su('admin');

$mail = new mailTest();
$mail->objectModel->config->webRoot = '/';

r($mail->sendmailTest(0, 0)) && p('processed') && e('1'); // 测试步骤1：空参数输入情况
r($mail->sendmailTest(0, 1)) && p('processed') && e('1'); // 测试步骤2：只传入actionID无objectID
r($mail->sendmailTest(1, 0)) && p('processed') && e('1'); // 测试步骤3：只传入objectID无actionID
r($mail->sendmailTest(1, 1)) && p('processed,objectID,actionID') && e('0,1,1'); // 测试步骤4：传入有效的objectID和actionID（捕获异常）
r($mail->sendmailTest(2, 2)) && p('processed') && e('0'); // 测试步骤5：测试正常邮件发送流程（捕获异常）
r($mail->sendmailTest(3, 3)) && p('processed') && e('1'); // 测试步骤6：测试其他类型邮件发送
r($mail->sendmailTest(5, 5)) && p('processed') && e('1'); // 测试步骤7：测试更多类型邮件发送