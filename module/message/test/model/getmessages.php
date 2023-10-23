#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/message.class.php';

su('admin');
zdTable('notify')->config('notify')->gen(10);

/**

title=测试 messageModel->getMessages();
cid=1
pid=1

拿取status为wait的数据信息 >> 1
拿取status为wait的数据信息 >> wait;wait;wait

*/


$message = new messageTest();

r($message->getMessagesTest('wait')) && p('1:id')                       && e('1');              //拿取status为wait的数据信息
r($message->getMessagesTest('wait')) && p('1:status;3:status;5:status') && e('wait;wait;wait'); //拿取status为wait的数据信息
