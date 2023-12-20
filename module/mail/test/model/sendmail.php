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
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$action = zdTable('action');
$action->execution->range('101');
$action->gen(2);
zdTable('story')->gen(2);
zdTable('product')->gen(2);

$mail = new mailTest();

r($mail->objectModel->sendmail(0, 0)) && p() && e('0'); //不传入任何参数
r($mail->objectModel->sendmail(0, 2)) && p() && e('0'); //只传入actionID
r($mail->objectModel->sendmail(2, 0)) && p() && e('0'); //只传入objectID

r($mail->objectModel->sendmail(2, 2)) && p() && e('0'); //测试为需求2发送邮件通知
