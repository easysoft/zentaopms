#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/mail.class.php';
su('admin');

/**

title=测试 mailModel->mergeMails();
cid=1
pid=1

获取邮件合并后的id >> 6,1
获取邮件合并后的主题 >> 主题6|主题1

*/

$mail = new mailTest();

r($mail->mergeMailsTest('admin')) && p('id') && e('6,1');              //获取邮件合并后的id
r($mail->mergeMailsTest('admin')) && p('subject') && e('主题6|主题1'); //获取邮件合并后的主题