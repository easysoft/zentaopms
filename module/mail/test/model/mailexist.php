#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

/**

title=测试 mailModel->mailExist();
cid=1
pid=1

检查系统是否至少存在邮件 >> 10001000@qq.com

*/

$mail = new mailTest();

r($mail->mailExistTest()) && p('email') && e('10001000@qq.com'); //检查系统是否至少存在邮件