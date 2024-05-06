#!/usr/bin/env php
<?php

/**

title=测试 mailModel->sendmail();
cid=0

- 不传入任何参数 @0
- 只传入actionID @0
- 只传入objectID @0
- 测试为需求2发送邮件通知 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';
su('admin');

$action = zenData('action');
$action->execution->range('101');
$action->gen(2);
zenData('story')->gen(2);
zenData('product')->gen(2);

$mail = new mailTest();
$mail->objectModel->config->webRoot = '/';

r($mail->objectModel->sendmail(0, 0)) && p() && e('0'); //不传入任何参数
r($mail->objectModel->sendmail(0, 2)) && p() && e('0'); //只传入actionID
r($mail->objectModel->sendmail(2, 0)) && p() && e('0'); //只传入objectID

r($mail->objectModel->sendmail(2, 2)) && p() && e('0'); //测试为需求2发送邮件通知
