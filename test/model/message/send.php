#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/message.class.php';
su('admin');

/**

title=测试 messageModel->send();
cid=1
pid=1

通过拿取todo表的第一条数据并且将actor赋值admin获取返回值 >> 0
通过拿取todo表的第0条数据并且将actor赋值admin获取返回值 >> 0

*/

$message = new messageTest();

r($message->sendTest('todo', '1', 'product', '1', 'admin')) && p() && e('0'); //通过拿取todo表的第一条数据并且将actor赋值admin获取返回值
r($message->sendTest('todo', '0', 'product', '1', 'admin')) && p() && e('0'); //通过拿取todo表的第0条数据并且将actor赋值admin获取返回值