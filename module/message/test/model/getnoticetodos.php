#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/message.class.php';

zdTable('todo')->config('todo')->gen(20);
zdTable('user')->gen(3);

/**

title=测试 messageModel->getNoticeTodos();
cid=1
pid=1

查询notice为空的todo的信息 >> 0

*/

$message = new messageTest();

$account = array('admin', 'user1', 'user2');

r($message->getNoticeTodosTest($account[0])) && p('0') && e('todo3,todo7,todo11'); // 查询 admin 的需要提示的待办信息
r($message->getNoticeTodosTest($account[1])) && p('0') && e('todo15,todo19');      // 查询 user1 的需要提示的待办信息
r($message->getNoticeTodosTest($account[2])) && p('0') && e('0');                  // 查询 user2 的需要提示的待办信息
