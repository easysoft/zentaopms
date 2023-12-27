#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/message.class.php';
su('admin');

zdTable('user')->gen(50);
zdTable('todo')->gen(1);
zdTable('testtask')->gen(1);
zdTable('meeting')->config('meeting')->gen(1);
zdTable('mr')->gen(1);
zdTable('release')->gen(1);
zdTable('task')->config('task')->gen(9);
zdTable('taskteam')->config('taskteam')->gen(9);
zdTable('story')->gen(1);
zdTable('action')->gen(2);
zdTable('product')->gen(10);

/**

title=测试 messageModel->getToList();
cid=1
pid=1

*/

$message = new messageTest();

$objectType = array('todo', 'testtask', 'mr', 'release', 'task', 'story');
$objectID   = array(1, 9);
$actionID   = array(0, 2);

r($message->getToListTest($objectType[0], $objectID[0], $actionID[0])) && p() && e('admin');       // 测试获取 todo 1 action 0 的 toList
r($message->getToListTest($objectType[1], $objectID[0], $actionID[0])) && p() && e('user3');       // 测试获取 testtask 1 action 0 的 toList
r($message->getToListTest($objectType[2], $objectID[0], $actionID[0])) && p() && e('admin,admin'); // 测试获取 mr 1 action 0 的 toList
r($message->getToListTest($objectType[3], $objectID[0], $actionID[0])) && p() && e('po1');         // 测试获取 release 1 action 0 的 toList
r($message->getToListTest($objectType[4], $objectID[0], $actionID[0])) && p() && e('dev1');        // 测试获取 task 1 action 0 的 toList
r($message->getToListTest($objectType[4], $objectID[1], $actionID[0])) && p() && e('user1,user2'); // 测试获取 task 9 action 0 的 toList
r($message->getToListTest($objectType[5], $objectID[0], $actionID[0])) && p() && e('admin');       // 测试获取 story 1 action 0 的 toList
r($message->getToListTest($objectType[5], $objectID[0], $actionID[1])) && p() && e('admin');       // 测试获取 story 1 action 0 的 toList
