#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getMailContent();
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
- 传入的objectType=mr @0
- 检查邮件内容包含需求名称 @1
- 检查邮件内容包含需求描述 @1
- 检查邮件内容包含备注 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$action = zdTable('action');
$action->execution->range(1);
$action->extra->range('``,`Fix:1`');
$action->gen(2);
zdTable('history')->gen(2);
$story = zdTable('story');
$story->version->range('1');
$story->gen(2);
zdTable('storyspec')->gen(2);
zdTable('product')->gen(2);

$mail = new mailTest();
$mail->objectModel->config->webRoot = '/';
$mail->objectModel->config->requestType = 'PATH_INFO';

r($mail->getMailContentTest('', 0, 0))       && p() && e('0'); //不传入任何参数
r($mail->getMailContentTest('', 1, 0))       && p() && e('0'); //只传入object
r($mail->getMailContentTest('', 0, 1))       && p() && e('0'); //只传入action
r($mail->getMailContentTest('story', 0, 0))  && p() && e('0'); //只传入objectType参数
r($mail->getMailContentTest('story', 0, 1))  && p() && e('0'); //不传入object
r($mail->getMailContentTest('story', 1, 0))  && p() && e('0'); //不传入action
r($mail->getMailContentTest('', 1, 1))       && p() && e('0'); //不传入objectType参数
r($mail->getMailContentTest('test', 1, 1))   && p() && e('0'); //传入的objectType不合法
r($mail->getMailContentTest('story', 10, 1)) && p() && e('0'); //传入的object不存在
r($mail->getMailContentTest('story', 1, 20)) && p() && e('0'); //传入的action不存在

r($mail->objectModel->getMailContent('mr', new stdclass(), new stdclass())) && p() && e('0'); //传入的objectType=mr

$storyContent = $mail->getMailContentTest('story', 1, 1);
r(strpos($storyContent, "<a href='/story-view-1.html' style='color: #333; text-decoration: underline;' >STORY #1 用户需求版本一1</a>") !== false) && p() && e('1'); //检查邮件内容包含需求名称
r(strpos($storyContent, "<div style='padding:5px;'>这是一个软件需求描述1</div>") !== false) && p() && e('1');                                                       //检查邮件内容包含需求描述
r(strpos($storyContent, '<div style="padding:5px;">这是一个系统日志测试备注1</div>') !== false) && p() && e('1');                                                   //检查邮件内容包含备注
