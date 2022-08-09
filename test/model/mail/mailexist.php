#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/mail.class.php';
su('admin');

/**

title=测试 mailModel->mailExist();
cid=1
pid=1

检查系统是否至少存在邮件 >> 10001000@qq.com

*/

$mail = new mailTest();

r($mail->mailExistTest()) && p('email') && e('10001000@qq.com'); //检查系统是否至少存在邮件