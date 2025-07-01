#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/message.unittest.class.php';

$notify = zenData('notify')->loadYaml('notify');
$notify->action->range('1');
$notify->gen(10);
zenData('action')->gen(1);
zenData('user')->gen(1);

su('admin');

/**

title=测试 messageModel->getMessages();
timeout=0
cid=0

- 测试获取status为 wait 的数据信息 @1,4,10
- 测试获取数据信息 @1,2,3,4,5,10

*/

$type = array('wait', '');

$message = new messageTest();

r($message->getMessagesTest($type[0])) && p() && e('1,4,10');       // 测试获取status为 wait 的数据信息
r($message->getMessagesTest($type[1])) && p() && e('1,2,3,4,5,10'); // 测试获取数据信息
