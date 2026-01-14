#!/usr/bin/env php
<?php

/**

title=测试 messageModel::getNoticeTodos();
timeout=0
cid=17052

- 测试admin用户的待办提醒情况 @0
- 测试user1用户的待办提醒情况 @0
- 测试user2用户的待办提醒情况 @0
- 测试test用户的待办提醒情况 @0
- 测试不存在用户的待办提醒情况 @0
- 测试返回数据格式的正确性 @0
- 测试空时间待办的处理 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('todo')->loadYaml('todo_getnoticetodos', false, 2)->gen(15);
zenData('user')->gen(5);

su('admin');

$message = new messageModelTest();

r($message->getNoticeTodosTest('admin', 'count')) && p() && e(0);                      // 测试admin用户的待办提醒情况
r($message->getNoticeTodosTest('user1', 'count')) && p() && e(0);                      // 测试user1用户的待办提醒情况
r($message->getNoticeTodosTest('user2', 'count')) && p() && e(0);                      // 测试user2用户的待办提醒情况
r($message->getNoticeTodosTest('test', 'count')) && p() && e(0);                       // 测试test用户的待办提醒情况
r($message->getNoticeTodosTest('nonexist', 'count')) && p() && e(0);                   // 测试不存在用户的待办提醒情况
r($message->getNoticeTodosTest('admin', 'ids')) && p() && e('0');                      // 测试返回数据格式的正确性
r($message->getNoticeTodosTest('test', 'ids')) && p() && e('0');                       // 测试空时间待办的处理