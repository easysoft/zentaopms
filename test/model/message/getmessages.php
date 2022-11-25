#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/message.class.php';
su('po82');

/**

title=测试 messageModel->getMessages();
cid=1
pid=1

拿取status为wait的数据信息 >> 1
拿取status为wait的数据信息 >> 114

*/

$message = new messageTest();

r($message->getMessagesTest('wait')) && p('1:id')     && e('1');   //拿取status为wait的数据信息
r($message->getMessagesTest('wait')) && p('2:action') && e('114'); //拿取status为wait的数据信息