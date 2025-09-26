#!/usr/bin/env php
<?php

/**

title=测试 mailModel::mailExist();
timeout=0
cid=0

- 执行mail模块的mailExistTest方法 属性hasEmail @1
- 执行mail模块的mailExistTest方法 属性hasEmail @1
- 执行mail模块的mailExistTest方法  @0
- 执行mail模块的mailExistTest方法  @0
- 执行mail模块的mailExistTest方法 属性hasEmail @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mail = new mailTest();

// 清除现有数据
$mail->objectModel->dao->delete()->from(TABLE_USER)->exec();

// 测试1：准备有邮箱的用户数据
$mail->objectModel->dao->insert(TABLE_USER)
    ->data(array(
        'id' => 1,
        'account' => 'admin',
        'email' => 'admin@test.com',
        'realname' => '管理员',
        'deleted' => '0'
    ))->exec();

r($mail->mailExistTest()) && p('hasEmail') && e('1');

// 测试2：更新用户邮箱
$mail->objectModel->dao->update(TABLE_USER)->set('email')->eq('admin@zentao.com')->where('account')->eq('admin')->exec();
r($mail->mailExistTest()) && p('hasEmail') && e('1');

// 测试3：所有用户邮箱为空
$mail->objectModel->dao->update(TABLE_USER)->set('email')->eq('')->exec();
r($mail->mailExistTest()) && p() && e('0');

// 测试4：删除所有用户记录
$mail->objectModel->dao->delete()->from(TABLE_USER)->exec();
r($mail->mailExistTest()) && p() && e('0');

// 测试5：部分用户有邮箱
$mail->objectModel->dao->insert(TABLE_USER)
    ->data(array(
        'id' => 2,
        'account' => 'user1',
        'email' => '',
        'realname' => '用户1',
        'deleted' => '0'
    ))->exec();

$mail->objectModel->dao->insert(TABLE_USER)
    ->data(array(
        'id' => 3,
        'account' => 'user2',
        'email' => 'user2@valid.com',
        'realname' => '用户2',
        'deleted' => '0'
    ))->exec();

r($mail->mailExistTest()) && p('hasEmail') && e('1');