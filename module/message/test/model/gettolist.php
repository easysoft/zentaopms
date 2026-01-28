#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('user')->gen(50);
zenData('todo')->gen(1);
zenData('testtask')->gen(1);
zenData('meeting')->loadYaml('meeting')->gen(1);
zenData('mr')->gen(1);
zenData('release')->gen(1);
zenData('task')->loadYaml('task')->gen(9);
zenData('taskteam')->loadYaml('taskteam')->gen(9);
zenData('story')->gen(1);
zenData('action')->gen(2);
zenData('product')->gen(10);

/**

title=测试 messageModel->getToList();
timeout=0
cid=17055

- 测试获取 todo 1 action 0 的 toList @admin
- 测试获取 testtask 1 action 0 的 toList @user3
- 测试获取 mr 1 action 0 的 toList @admin,admin

- 测试获取 release 1 action 0 的 toList @admin,,po1,dev1

- 测试获取 task 1 action 0 的 toList @dev1
- 测试获取 task 9 action 0 的 toList @0
- 测试获取 story 1 action 0 的 toList @admin
- 测试获取 story 1 action 0 的 toList @admin

*/

$message = new messageModelTest();

$objectType = array('todo', 'testtask', 'mr', 'release', 'task', 'story');
$objectID   = array(1, 9);
$actionID   = array(0, 2);

r($message->getToListTest($objectType[0], $objectID[0], $actionID[0])) && p() && e('admin');           // 测试获取 todo 1 action 0 的 toList
r($message->getToListTest($objectType[1], $objectID[0], $actionID[0])) && p() && e('user3');           // 测试获取 testtask 1 action 0 的 toList
r($message->getToListTest($objectType[2], $objectID[0], $actionID[0])) && p() && e('admin,admin');     // 测试获取 mr 1 action 0 的 toList
r($message->getToListTest($objectType[3], $objectID[0], $actionID[0])) && p() && e('admin,,po1,dev1'); // 测试获取 release 1 action 0 的 toList
r($message->getToListTest($objectType[4], $objectID[0], $actionID[0])) && p() && e('dev1');            // 测试获取 task 1 action 0 的 toList
r($message->getToListTest($objectType[4], $objectID[1], $actionID[0])) && p() && e('0');               // 测试获取 task 9 action 0 的 toList
r($message->getToListTest($objectType[5], $objectID[0], $actionID[0])) && p() && e('admin');           // 测试获取 story 1 action 0 的 toList
r($message->getToListTest($objectType[5], $objectID[0], $actionID[1])) && p() && e('admin');           // 测试获取 story 1 action 0 的 toList