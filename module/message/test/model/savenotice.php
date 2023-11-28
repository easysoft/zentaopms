#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/message.class.php';

zdTable('story')->gen(2);
zdTable('task')->config('task')->gen(7);
zdTable('notify')->gen(0);
zdTable('action')->gen(20);
zdTable('user')->gen(2);

su('admin');

/**

title=测试 messageModel->saveNotice();
cid=1
pid=1

*/

$objectType = array('story', 'task', 'user');
$objectID   = array(2, 6, 0);
$actionType = array('opened', 'edited', 'nothing');
$actionID   = array(2, 6, 0);
$actor      = array('admin', 'user1', '', 'empty');

$message = new messageTest();
r($message->saveNoticeTest($objectType[0], $objectID[0], $actionType[0], $actionID[0], $actor[0])) && p('id,objectType,action,createdBy') && e('1,message,2,admin'); // 发送 需求 2 动态 2 用户 admin 的消息
r($message->saveNoticeTest($objectType[1], $objectID[1], $actionType[1], $actionID[1], $actor[0])) && p('id,objectType,action,createdBy') && e('2,message,6,admin'); // 发送 任务 6 动态 6 用户 admin 的消息
r($message->saveNoticeTest($objectType[2], $objectID[2], $actionType[2], $actionID[2], $actor[0])) && p('id,objectType,action,createdBy') && e('0,0,0,0');           // 发送 反馈 0 动态 0 用户 admin 的消息

r($message->saveNoticeTest($objectType[0], $objectID[0], $actionType[0], $actionID[0], $actor[1])) && p('id,objectType,action,createdBy') && e('3,message,2,user1'); // 发送 需求 2 动态 2 用户 user1 的消息
r($message->saveNoticeTest($objectType[1], $objectID[1], $actionType[1], $actionID[1], $actor[1])) && p('id,objectType,action,createdBy') && e('4,message,6,user1'); // 发送 任务 6 动态 6 用户 user1 的消息
r($message->saveNoticeTest($objectType[2], $objectID[2], $actionType[2], $actionID[2], $actor[1])) && p('id,objectType,action,createdBy') && e('0,0,0,0');           // 发送 反馈 0 动态 0 用户 user1 的消息

r($message->saveNoticeTest($objectType[0], $objectID[0], $actionType[0], $actionID[0], $actor[2])) && p('id,objectType,action,createdBy') && e('5,message,2,admin'); // 发送 需求 2 动态 2 不传 actor 的消息
r($message->saveNoticeTest($objectType[1], $objectID[1], $actionType[1], $actionID[1], $actor[2])) && p('id,objectType,action,createdBy') && e('6,message,6,admin'); // 发送 任务 6 动态 6 不传 actor 的消息
r($message->saveNoticeTest($objectType[2], $objectID[2], $actionType[2], $actionID[2], $actor[2])) && p('id,objectType,action,createdBy') && e('0,0,0,0');           // 发送 反馈 0 动态 0 不传 actor 的消息

r($message->saveNoticeTest($objectType[0], $objectID[0], $actionType[0], $actionID[0], $actor[3])) && p('id') && e('0'); // 发送 需求 2 动态 2 未登录 的消息
r($message->saveNoticeTest($objectType[1], $objectID[1], $actionType[1], $actionID[1], $actor[3])) && p('id') && e('0'); // 发送 任务 6 动态 6 未登录 的消息
r($message->saveNoticeTest($objectType[2], $objectID[2], $actionType[2], $actionID[2], $actor[3])) && p('id') && e('0'); // 发送 反馈 0 动态 0 未登录 的消息
