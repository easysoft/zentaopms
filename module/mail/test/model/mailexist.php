#!/usr/bin/env php
<?php

/**

title=测试 mailModel->mailExist();
cid=0

- 检查系统是否至少存在邮箱 @1
- 当系统不存在邮箱 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

zdTable('user')->gen(5);

$mail = new mailTest();

$user = $mail->mailExistTest();
r($user->email == '10001000@qq.com') && p() && e('1'); //检查系统是否至少存在邮箱

$mail->objectModel->dao->update(TABLE_USER)->set('email')->eq('')->exec();
r($mail->mailExistTest()) && p() && e('0'); //当系统不存在邮箱
