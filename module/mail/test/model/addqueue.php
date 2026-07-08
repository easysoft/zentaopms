#!/usr/bin/env php
<?php

/**

title=测试 mailModel::addQueue();
timeout=0
cid=17002

- 执行mailTest模块的addQueueTest方法，参数是'', ''  @没有数据提交
- 执行mailTest模块的addQueueTest方法，参数是'user3', '测试邮件主题', '测试邮件内容' 
 - 属性subject @测试邮件主题
 - 属性objectType @mail
 - 属性createdBy @admin
- 执行mailTest模块的addQueueTest方法，参数是'user3, admin', '测试主题', '测试内容', '', false 属性toList @user3
- 执行mailTest模块的addQueueTest方法，参数是'user3, admin', '测试主题2', '测试内容', '', true 
 - 属性toList @user3,admin
- 执行mailTest模块的addQueueTest方法，参数是'user1', '抄送测试', '抄送内容', 'user2, user3' 
 - 属性ccList @user2,user3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;

$tester->dao->delete()->from(TABLE_NOTIFY)->where('id')->gt(0)->exec();
$tester->dao->delete()->from(TABLE_USER)->where('id')->gt(0)->exec();

$users = array(
    array('account' => 'admin', 'realname' => '管理员', 'email' => 'admin@test.com', 'gender' => 'f'),
    array('account' => 'user1', 'realname' => '用户1', 'email' => 'user1@test.com', 'gender' => 'm'),
    array('account' => 'user2', 'realname' => '用户2', 'email' => 'user2@test.com', 'gender' => 'f'),
    array('account' => 'user3', 'realname' => '用户3', 'email' => 'user3@test.com', 'gender' => 'm'),
    array('account' => 'user4', 'realname' => '用户4', 'email' => 'user4@test.com', 'gender' => 'f'),
);

foreach($users as $userData)
{
    $user = new stdClass();
    $user->company  = 1;
    $user->type     = 'inside';
    $user->dept     = 1;
    $user->account  = $userData['account'];
    $user->password = md5('123456');
    $user->role     = 'qa';
    $user->realname = $userData['realname'];
    $user->gender   = $userData['gender'];
    $user->email    = $userData['email'];
    $user->visions  = 'rnd,lite,or';
    $user->deleted  = '0';
    $tester->dao->insert(TABLE_USER)->data($user)->exec();
}

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$mailTest = new mailModelTest();
$mailTest->setUserState('admin');

// 5. 必须包含至少5个测试步骤
r($mailTest->addQueueTest('', '')) && p() && e('没有数据提交');
r($mailTest->addQueueTest('user3', '测试邮件主题', '测试邮件内容')) && p('subject,objectType,createdBy') && e('测试邮件主题,mail,admin');
r($mailTest->addQueueTest('user3,admin', '测试主题', '测试内容', '', false)) && p('toList') && e('user3');
r($mailTest->addQueueTest('user3,admin', '测试主题2', '测试内容', '', true)) && p('toList', '|') && e('user3,admin');
r($mailTest->addQueueTest('user1', '抄送测试', '抄送内容', 'user2,user3')) && p('ccList', '|') && e('user2,user3');
