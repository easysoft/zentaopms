#!/usr/bin/env php
<?php

/**

title=测试 mailModel::mailExist();
timeout=0
cid=0

- 执行mail模块的mailExistTest方法 属性email @admin@test.com
- 执行mail模块的mailExistTest方法 属性email @admin@zentao.com
- 执行mail模块的mailExistTest方法  @0
- 执行mail模块的mailExistTest方法  @0
- 执行mail模块的mailExistTest方法 属性email @user2@valid.com

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4');
$user->email->range('admin@test.com,user1@test.com,user2@test.com,,');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->deleted->range('0{5}');
$user->gen(5);

su('admin');

$mail = new mailTest();

r($mail->mailExistTest()) && p('email') && e('admin@test.com');

$user->account->range('admin,user1,user2');
$user->email->range('admin@zentao.com,user1@zentao.com,user2@zentao.com');
$user->gen(3);
r($mail->mailExistTest()) && p('email') && e('admin@zentao.com');

$mail->objectModel->dao->update(TABLE_USER)->set('email')->eq('')->exec();
r($mail->mailExistTest()) && p() && e('0');

$mail->objectModel->dao->delete()->from(TABLE_USER)->exec();
r($mail->mailExistTest()) && p() && e('0');

$user->account->range('user1,user2,user3');
$user->email->range(',user2@valid.com,');
$user->gen(3);
r($mail->mailExistTest()) && p('email') && e('user2@valid.com');