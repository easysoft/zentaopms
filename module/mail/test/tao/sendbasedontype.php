#!/usr/bin/env php
<?php

/**

title=测试 mailModel->sendBasedOnType();
cid=0

- 不传入任何参数 @0
- 只传入object @0
- 只传入action @0
- 只传入objectType参数 @0
- 不传入object @0
- 不传入action @0
- 不传入objectType参数 @0
- 传入的objectType不合法 @0
- 传入的object不存在 @0
- 传入的action不存在 @0
- 对需求2发信 @0
- 对成功的MR1发信 @0
- 对失败的MR2发信 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$action = zdTable('action');
$action->execution->range(1);
$action->objectID->range('1,2');
$action->objectType->range('product,story,mr,mr,review');
$action->action->range('opened,opened,compilepass,compilefail,opened');
$action->extra->range('``,`Fix:1`');
$action->gen(5);
zdTable('history')->gen(2);
$story = zdTable('story');
$story->version->range('1');
$story->gen(2);
zdTable('storyspec')->gen(2);
zdTable('mr')->gen(1);
zdTable('product')->gen(2);

$mail = new mailTest();
$mail->objectModel->config->webRoot = '/';

r($mail->sendBasedOnTypeTest('', 0, 0))       && p() && e('0'); //不传入任何参数
r($mail->sendBasedOnTypeTest('', 1, 0))       && p() && e('0'); //只传入object
r($mail->sendBasedOnTypeTest('', 0, 1))       && p() && e('0'); //只传入action
r($mail->sendBasedOnTypeTest('story', 0, 0))  && p() && e('0'); //只传入objectType参数
r($mail->sendBasedOnTypeTest('story', 0, 1))  && p() && e('0'); //不传入object
r($mail->sendBasedOnTypeTest('story', 1, 0))  && p() && e('0'); //不传入action
r($mail->sendBasedOnTypeTest('', 1, 1))       && p() && e('0'); //不传入objectType参数
r($mail->sendBasedOnTypeTest('test', 1, 1))   && p() && e('0'); //传入的objectType不合法
r($mail->sendBasedOnTypeTest('story', 10, 1)) && p() && e('0'); //传入的object不存在
r($mail->sendBasedOnTypeTest('story', 1, 20)) && p() && e('0'); //传入的action不存在

r($mail->sendBasedOnTypeTest('story', 2, 2)) && p() && e('0'); //对需求2发信
r($mail->sendBasedOnTypeTest('mr', 1, 3))    && p() && e('0'); //对成功的MR1发信
r($mail->sendBasedOnTypeTest('mr', 2, 4))    && p() && e('0'); //对失败的MR2发信
