#!/usr/bin/env php
<?php

/**

title=测试 mailModel::sendmail();
timeout=0
cid=17017

- 测试步骤1：空参数输入情况属性processed @1
- 测试步骤2：只传入actionID无objectID属性processed @1
- 测试步骤3：只传入objectID无actionID属性processed @1
- 测试步骤4：传入有效的objectID和actionID属性processed @0
- 测试步骤5：测试不同对象类型的邮件发送属性processed @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 手动插入基础测试数据，避免zendata工具路径问题
global $tester;

// 插入基础action数据
$tester->dao->delete()->from(TABLE_ACTION)->where('id')->gt(0)->exec();
for($i = 1; $i <= 5; $i++)
{
    $action = new stdClass();
    $action->id = $i;
    $action->objectType = $i <= 2 ? 'story' : 'task';
    $action->objectID = $i <= 3 ? $i : $i - 2;
    $action->actor = 'admin';
    $action->action = 'opened';
    $action->date = '2024-01-01 00:00:00';
    $action->comment = '测试注释';
    $tester->dao->insert(TABLE_ACTION)->data($action)->exec();
}

// 插入基础用户数据
$tester->dao->delete()->from(TABLE_USER)->where('account')->ne('system')->exec();
$users = array(
    array('id' => 1, 'account' => 'admin', 'realname' => '管理员', 'email' => 'admin@test.com'),
    array('id' => 2, 'account' => 'user1', 'realname' => '用户1', 'email' => 'user1@test.com'),
    array('id' => 3, 'account' => 'user2', 'realname' => '用户2', 'email' => 'user2@test.com'),
);
foreach($users as $userData)
{
    $user = new stdClass();
    foreach($userData as $key => $value) $user->$key = $value;
    $user->password = md5('123456');
    $user->deleted = '0';
    $user->type = 'inside';
    $user->dept = 1;
    $user->role = 'dev';
    $tester->dao->insert(TABLE_USER)->data($user)->exec();
}

su('admin');

$mail = new mailModelTest();

r($mail->sendmailTest(0, 0)) && p('processed') && e('1'); // 测试步骤1：空参数输入情况
r($mail->sendmailTest(0, 1)) && p('processed') && e('1'); // 测试步骤2：只传入actionID无objectID
r($mail->sendmailTest(1, 0)) && p('processed') && e('1'); // 测试步骤3：只传入objectID无actionID
r($mail->sendmailTest(1, 1)) && p('processed') && e('0'); // 测试步骤4：传入有效的objectID和actionID
r($mail->sendmailTest(2, 2)) && p('processed') && e('0'); // 测试步骤5：测试不同对象类型的邮件发送