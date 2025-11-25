#!/usr/bin/env php
<?php

/**

title=测试 messageModel::getMessages();
timeout=0
cid=17051

- 测试获取全部消息 @8
- 测试获取wait状态消息 @4
- 测试获取sended状态消息 @3
- 测试使用id倒序排列
 - 属性hasId @1
 - 属性hasObjectType @1
 - 属性hasToList @1
 - 属性hasStatus @1
 - 属性hasData @1
- 测试使用createdDate排序属性objectType @message
- 测试无效status参数 @0
- 测试read状态消息 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/message.unittest.class.php';

// 先生成action表数据，确保有正确的id
$action = zenData('action');
$action->id->range('1-10');
$action->objectType->range('message{10}');
$action->vision->range('rnd{10}');
$action->gen(10);

// 生成notify表数据
$notify = zenData('notify');
$notify->objectType->range('message{10}');
$notify->toList->range('admin{8},test1{2}');
$notify->status->range('wait{4},sended{3},read{2},fail{1}');
$notify->action->range('1-10');
$notify->gen(10);

// 手动更新toList格式为正确的格式
global $tester;
$tester->dao->update(TABLE_NOTIFY)->set('toList')->eq(',admin,')->where('toList')->eq('admin')->exec();
$tester->dao->update(TABLE_NOTIFY)->set('toList')->eq(',test1,')->where('toList')->eq('test1')->exec();

zenData('user')->gen(5);

su('admin');

$message = new messageTest();

r($message->getMessagesTest('all', 'createdDate', 'count')) && p() && e('8');                    // 测试获取全部消息
r($message->getMessagesTest('wait', 'createdDate', 'count')) && p() && e('4');                    // 测试获取wait状态消息
r($message->getMessagesTest('sended', 'createdDate', 'count')) && p() && e('3');                  // 测试获取sended状态消息
r($message->getMessagesTest('all', 'id_desc', 'structure')) && p('hasId,hasObjectType,hasToList,hasStatus,hasData') && e('1,1,1,1,1'); // 测试使用id倒序排列
r($message->getMessagesTest('all', 'createdDate_desc', 'first')) && p('objectType') && e('message'); // 测试使用createdDate排序
r($message->getMessagesTest('invalid', 'createdDate', 'count')) && p() && e('0');                 // 测试无效status参数
r($message->getMessagesTest('read', 'createdDate', 'count')) && p() && e('1');                    // 测试read状态消息