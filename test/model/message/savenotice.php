#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/message.class.php';
su('admin');

/**

title=测试 messageModel->saveNotice();
cid=1
pid=1

通过拿取todo表的第一条数据并且将actor赋值admin获取返回值 >> 0
通过拿取task表的第一条数据并且将actor赋值admin获取返回值 >> 0
通过拿取todo表的第一条数据并且不给actor赋值获取返回值 >> 0
通过拿取todo表的第一条数据并且给actor赋值user1获取返回值 >> 0

*/

$message = new messageTest();

r($message->saveNoticeTest('todo', '1', 'product', '1', 'admin')) && p() && e('0'); //通过拿取todo表的第一条数据并且将actor赋值admin获取返回值
r($message->saveNoticeTest('task', '1', 'product', '1', 'admin')) && p() && e('0'); //通过拿取task表的第一条数据并且将actor赋值admin获取返回值
r($message->saveNoticeTest('todo', '1', 'product', '1', ''))      && p() && e('0'); //通过拿取todo表的第一条数据并且不给actor赋值获取返回值
r($message->saveNoticeTest('todo', '1', 'product', '1', 'user1')) && p() && e('0'); //通过拿取todo表的第一条数据并且给actor赋值user1获取返回值