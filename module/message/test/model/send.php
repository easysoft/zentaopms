#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/message.unittest.class.php';

zenData('story')->gen(2);
zenData('notify')->gen(0);
zenData('action')->gen(2);
zenData('user')->gen(1);

su('admin');

/**

title=测试 messageModel->send();
cid=17058

- 发送 需求 2 动态 2 的消息 @0
- 发送 需求 2 动态 2 的消息 @0
- 发送 需求 2 动态 0 的消息 @0
- 发送 需求 2 动态 0 的消息 @0
- 发送 反馈 0 动态 0 的消息 @0

*/

$message = new messageTest();

$objectType = array('story', 'feedback');
$objectID   = array(2, 0);
$actionType = array('opened', 'nothing');
$actionID   = array(2, 0);
$actor      = array('admin', '');

r($message->sendTest($objectType[0], $objectID[0], $actionType[0], $actionID[0], $actor[0])) && p(0) && e('0'); // 发送 需求 2 动态 2 的消息
r($message->sendTest($objectType[0], $objectID[0], $actionType[1], $actionID[0], $actor[0])) && p(0) && e('0'); // 发送 需求 2 动态 2 的消息
r($message->sendTest($objectType[0], $objectID[0], $actionType[1], $actionID[1], $actor[0])) && p(0) && e('0'); // 发送 需求 2 动态 0 的消息
r($message->sendTest($objectType[0], $objectID[0], $actionType[1], $actionID[1], $actor[1])) && p(0) && e('0'); // 发送 需求 2 动态 0 的消息
r($message->sendTest($objectType[1], $objectID[1], $actionType[1], $actionID[1], $actor[1])) && p(0) && e('0'); // 发送 反馈 0 动态 0 的消息
