#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('action')->config('action')->gen(99);
zdTable('project')->gen(20, true, false);
zdTable('project')->config('execution')->gen(90, false, false);
zdTable('product')->config('product')->gen(50, true, false);
zdTable('projectstory')->gen(10);
zdTable('productplan')->gen(10);
zdTable('story')->gen(10);
zdTable('task')->gen(20);
zdTable('bug')->gen(10);
zdTable('kanban')->gen(1);
zdTable('build')->gen(10);
zdTable('release')->gen(10);
zdTable('testsuite')->gen(10);
zdTable('testtask')->gen(10);
zdTable('assetlib')->gen(10);
zdTable('user')->gen(10);

su('admin');

/**

title=测试 actionModel->getList();
timeout=0
cid=1

- 测试获取对象类型 story 对象ID 1 的动态信息 @nochanged
- 测试获取对象类型 story 对象ID 2 的动态信息 @link
- 测试获取对象类型 story 对象ID 3 的动态信息 @link
- 测试获取对象类型 story 对象ID 4 的动态信息 @link
- 测试获取对象类型 story 对象ID 5 的动态信息 @nochanged
- 测试获取对象类型 story 对象ID 6 的动态信息 @link
- 测试获取对象类型 story 对象ID 7 的动态信息 @nochanged
- 测试获取对象类型 story 对象ID 8 的动态信息 @link
- 测试获取对象类型 story 对象ID 9 的动态信息 @link
- 测试获取对象类型 story 对象ID 10 的动态信息 @link
- 测试获取对象类型 story 对象ID 11 的动态信息 @link
- 测试获取对象类型 story 对象ID 12 的动态信息 @link
- 测试获取对象类型 story 对象ID 13 的动态信息 @link
- 测试获取对象类型 story 对象ID 14 的动态信息 @nochanged
- 测试获取对象类型 story 对象ID 15 的动态信息 @link
- 测试获取对象类型 story 对象ID 16 的动态信息 @link
- 测试获取对象类型 story 对象ID 17 的动态信息 @link
- 测试获取对象类型 story 对象ID 18 的动态信息 @nochanged
- 测试获取对象类型 story 对象ID 19 的动态信息 @link
- 测试获取对象类型 story 对象ID 20 的动态信息 @nochanged
- 测试获取对象类型 story 对象ID 21 的动态信息 @link
- 测试获取对象类型 story 对象ID 22 的动态信息 @link
- 测试获取对象类型 story 对象ID 23 的动态信息 @link
- 测试获取对象类型 story 对象ID 24 的动态信息 @link
- 测试获取对象类型 story 对象ID 25 的动态信息 @link
- 测试获取对象类型 story 对象ID 26 的动态信息 @link
- 测试获取对象类型 story 对象ID 27 的动态信息 @nochanged
- 测试获取对象类型 bug 对象ID 1 的动态信息 @link
- 测试获取对象类型 bug 对象ID 2 的动态信息 @link
- 测试获取对象类型 bug 对象ID 3 的动态信息 @link
- 测试获取对象类型 feedback 对象ID 1 的动态信息 @nochanged
- 测试获取对象类型 ticket 对象ID 1 的动态信息 @nochanged
- 测试获取对象类型 task 对象ID 1 的动态信息 @link
- 测试获取对象类型 module 对象ID 1 的动态信息 @nochanged
- 测试获取对象类型 story 对象ID 28 的动态信息 @link
- 测试获取对象类型 task 对象ID 2 的动态信息 @link
- 测试获取对象类型 task 对象ID 3 的动态信息 @nochanged
- 测试获取对象类型 task 对象ID 4 的动态信息 @link
- 测试获取对象类型 task 对象ID 5 的动态信息 @title
- 测试获取对象类型 story 对象ID 29 的动态信息 @link
- 测试获取对象类型 story 对象ID 30 的动态信息 @title
- 测试获取对象类型 release 对象ID 1 的动态信息 @link
- 测试获取对象类型 release 对象ID 2 的动态信息 @nochanged
- 测试获取对象类型 case 对象ID 1 的动态信息 @link
- 测试获取对象类型 case 对象ID 2 的动态信息 @nochanged
- 测试获取对象类型 story 对象ID 31 的动态信息 @nochanged
- 测试获取对象类型 todo 对象ID 1 的动态信息 @link
- 测试获取对象类型 todo 对象ID 2 的动态信息 @link
- 测试获取对象类型 todo 对象ID 3 的动态信息 @link
- 测试获取对象类型 story 对象ID 32 的动态信息 @nochanged
- 测试获取对象类型 story 对象ID 33 的动态信息 @link
- 测试获取对象类型 story 对象ID 34 的动态信息 @link
- 测试获取对象类型 story 对象ID 35 的动态信息 @link
- 测试获取对象类型 todo 对象ID 4 的动态信息 @nochanged
- 测试获取对象类型 bug 对象ID 4 的动态信息 @link
- 测试获取对象类型 bug 对象ID 5 的动态信息 @link
- 测试获取对象类型 bug 对象ID 6 的动态信息 @link
- 测试获取对象类型 todo 对象ID 5 的动态信息 @nochanged
- 测试获取对象类型 task 对象ID 6 的动态信息 @link
- 测试获取对象类型 task 对象ID 7 的动态信息 @link
- 测试获取对象类型 task 对象ID 8 的动态信息 @link
- 测试获取对象类型 task 对象ID 9 的动态信息 @link
- 测试获取对象类型 task 对象ID 10 的动态信息 @link
- 测试获取对象类型 task 对象ID 11 的动态信息 @link
- 测试获取对象类型 feedback 对象ID 2 的动态信息 @nochanged
- 测试获取对象类型 feedback 对象ID 3 的动态信息 @nochanged
- 测试获取对象类型 feedback 对象ID 4 的动态信息 @nochanged
- 测试获取对象类型 feedback 对象ID 5 的动态信息 @nochanged
- 测试获取对象类型 feedback 对象ID 6 的动态信息 @nochanged
- 测试获取对象类型 feedback 对象ID 7 的动态信息 @nochanged
- 测试获取对象类型 story 对象ID 36 的动态信息 @link
- 测试获取对象类型 story 对象ID 37 的动态信息 @link
- 测试获取对象类型 story 对象ID 38 的动态信息 @link
- 测试获取对象类型 story 对象ID 39 的动态信息 @link
- 测试获取对象类型 story 对象ID 40 的动态信息 @link
- 测试获取对象类型 testtask 对象ID 1 的动态信息 @link
- 测试获取对象类型 testtask 对象ID 2 的动态信息 @link
- 测试获取对象类型 testtask 对象ID 3 的动态信息 @link
- 测试获取对象类型 task 对象ID 12 的动态信息 @nochanged
- 测试 开源版 获取对象类型 risk 对象ID 1 的动态信息 @nochanged
- 测试 开源版 获取对象类型 isue 对象ID 1 的动态信息 @nochanged
- 测试 开源版 获取对象类型 opportunity 对象ID 1 的动态信息 @nochanged
- 测试获取对象类型 task 对象ID 12 的动态信息 @link
- 测试 开源版 获取对象类型 risk 对象ID 1 的动态信息 @link
- 测试 开源版 获取对象类型 isue 对象ID 1 的动态信息 @link
- 测试 开源版 获取对象类型 opportunity 对象ID 1 的动态信息 @link
- 测试获取对象类型 execution 对象ID 1 的动态信息 @link
- 测试获取对象类型 execution 对象ID 2 的动态信息 @link
- 测试获取对象类型 execution 对象ID 3 的动态信息 @link
- 测试获取对象类型 execution 对象ID 4 的动态信息 @nochanged
- 测试获取对象类型 project 对象ID 1 的动态信息 @link
- 测试获取对象类型 project 对象ID 2 的动态信息 @link
- 测试获取对象类型 project 对象ID 3 的动态信息 @link
- 测试获取对象类型 project 对象ID 4 的动态信息 @nochanged
- 测试获取对象类型 story 对象ID 41 的动态信息 @link
- 测试获取对象类型 story 对象ID 42 的动态信息 @link
- 测试获取对象类型 story 对象ID 43 的动态信息 @link
- 测试获取对象类型 bug 对象ID 7 的动态信息 @link
- 测试获取对象类型 bug 对象ID 8 的动态信息 @link
- 测试获取对象类型 task 对象ID 13 的动态信息 @nochanged
- 测试获取对象类型 task 对象ID 14 的动态信息 @nochanged
- 测试获取对象类型 task 对象ID 16 的动态信息 @nochanged
- 测试获取对象类型 task 对象ID 15 的动态信息 @nochanged

*/

$objectType  = array('story', 'task', 'bug', 'feedback', 'ticket', 'module', 'release', 'case', 'todo', 'testtask', 'risk', 'issue', 'opportunity', 'execution', 'project');
$storyID     = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43);
$taskID      = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16);
$bugID       = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
$feedbackID  = array(1, 2, 3, 4, 5, 6, 7);
$hasOneID    = array(1);
$hasTwoID    = array(1, 2);
$todoID      = array(1, 2, 3, 4, 5);
$testtaskID  = array(1, 2, 3);
$executionID = array(101, 102, 103, 104);
$projectID   = array(11, 12, 13, 14);

$action = new actionTest();

// 操作以 linked2 或 unlinked2 开头
r($action->getListTest($objectType[0], $storyID[0]))  && p() && e('nochanged'); // 测试获取对象类型 story 对象ID 1 的动态信息
r($action->getListTest($objectType[0], $storyID[1]))  && p() && e('link');      // 测试获取对象类型 story 对象ID 2 的动态信息
r($action->getListTest($objectType[0], $storyID[2]))  && p() && e('link');      // 测试获取对象类型 story 对象ID 3 的动态信息
r($action->getListTest($objectType[0], $storyID[3]))  && p() && e('link');      // 测试获取对象类型 story 对象ID 4 的动态信息
r($action->getListTest($objectType[0], $storyID[4]))  && p() && e('nochanged'); // 测试获取对象类型 story 对象ID 5 的动态信息
r($action->getListTest($objectType[0], $storyID[5]))  && p() && e('link');      // 测试获取对象类型 story 对象ID 6 的动态信息
r($action->getListTest($objectType[0], $storyID[6]))  && p() && e('nochanged'); // 测试获取对象类型 story 对象ID 7 的动态信息
r($action->getListTest($objectType[0], $storyID[7]))  && p() && e('link');      // 测试获取对象类型 story 对象ID 8 的动态信息
r($action->getListTest($objectType[0], $storyID[8]))  && p() && e('link');      // 测试获取对象类型 story 对象ID 9 的动态信息
r($action->getListTest($objectType[0], $storyID[9]))  && p() && e('link');      // 测试获取对象类型 story 对象ID 10 的动态信息
r($action->getListTest($objectType[0], $storyID[10])) && p() && e('link');      // 测试获取对象类型 story 对象ID 11 的动态信息
r($action->getListTest($objectType[0], $storyID[11])) && p() && e('link');      // 测试获取对象类型 story 对象ID 12 的动态信息
r($action->getListTest($objectType[0], $storyID[12])) && p() && e('link');      // 测试获取对象类型 story 对象ID 13 的动态信息
r($action->getListTest($objectType[0], $storyID[13])) && p() && e('nochanged'); // 测试获取对象类型 story 对象ID 14 的动态信息
r($action->getListTest($objectType[0], $storyID[14])) && p() && e('link');      // 测试获取对象类型 story 对象ID 15 的动态信息
r($action->getListTest($objectType[0], $storyID[15])) && p() && e('link');      // 测试获取对象类型 story 对象ID 16 的动态信息
r($action->getListTest($objectType[0], $storyID[16])) && p() && e('link');      // 测试获取对象类型 story 对象ID 17 的动态信息
r($action->getListTest($objectType[0], $storyID[17])) && p() && e('nochanged'); // 测试获取对象类型 story 对象ID 18 的动态信息
r($action->getListTest($objectType[0], $storyID[18])) && p() && e('link');      // 测试获取对象类型 story 对象ID 19 的动态信息
r($action->getListTest($objectType[0], $storyID[19])) && p() && e('nochanged'); // 测试获取对象类型 story 对象ID 20 的动态信息
r($action->getListTest($objectType[0], $storyID[20])) && p() && e('link');      // 测试获取对象类型 story 对象ID 21 的动态信息
r($action->getListTest($objectType[0], $storyID[21])) && p() && e('link');      // 测试获取对象类型 story 对象ID 22 的动态信息
r($action->getListTest($objectType[0], $storyID[22])) && p() && e('link');      // 测试获取对象类型 story 对象ID 23 的动态信息
r($action->getListTest($objectType[0], $storyID[23])) && p() && e('link');      // 测试获取对象类型 story 对象ID 24 的动态信息
r($action->getListTest($objectType[0], $storyID[24])) && p() && e('link');      // 测试获取对象类型 story 对象ID 25 的动态信息
r($action->getListTest($objectType[0], $storyID[25])) && p() && e('link');      // 测试获取对象类型 story 对象ID 26 的动态信息
r($action->getListTest($objectType[0], $storyID[26])) && p() && e('nochanged'); // 测试获取对象类型 story 对象ID 27 的动态信息

// 操作是 tostory 对象类型 bug
r($action->getListTest($objectType[2], $bugID[0])) && p() && e('link'); // 测试获取对象类型 bug 对象ID 1 的动态信息
r($action->getListTest($objectType[2], $bugID[1])) && p() && e('link'); // 测试获取对象类型 bug 对象ID 2 的动态信息
r($action->getListTest($objectType[2], $bugID[2])) && p() && e('link'); // 测试获取对象类型 bug 对象ID 3 的动态信息

// 操作是 tostory 对象类型 feedback
r($action->getListTest($objectType[3], $feedbackID[0])) && p() && e('nochanged'); // 测试获取对象类型 feedback 对象ID 1 的动态信息

// 操作是 tostory 对象类型 ticket
r($action->getListTest($objectType[4], $hasOneID[0])) && p() && e('nochanged'); // 测试获取对象类型 ticket 对象ID 1 的动态信息

// 操作是 moved 对象类型是 task
r($action->getListTest($objectType[1], $taskID[0])) && p() && e('link'); // 测试获取对象类型 task 对象ID 1 的动态信息

// 操作是 moved 对象类型是 module
r($action->getListTest($objectType[5], $hasOneID[0])) && p() && e('nochanged'); // 测试获取对象类型 module 对象ID 1 的动态信息

// 操作 frombug
r($action->getListTest($objectType[0], $storyID[27])) && p() && e('link'); // 测试获取对象类型 story 对象ID 28 的动态信息

// 操作 importedcard 对象存在
r($action->getListTest($objectType[1], $taskID[1])) && p() && e('link'); // 测试获取对象类型 task 对象ID 2 的动态信息

// 操作 importedcard 对象不存在
r($action->getListTest($objectType[1], $taskID[2])) && p() && e('nochanged'); // 测试获取对象类型 task 对象ID 3 的动态信息

// 操作 createchildren 关联任务非空
r($action->getListTest($objectType[1], $taskID[3])) && p() && e('link'); // 测试获取对象类型 task 对象ID 4 的动态信息

// 操作 createchildren 关联任务为空
r($action->getListTest($objectType[1], $taskID[4])) && p() && e('title'); // 测试获取对象类型 task 对象ID 5 的动态信息

// 操作 createrequirements 关联需求非空
r($action->getListTest($objectType[0], $storyID[28])) && p() && e('link'); // 测试获取对象类型 story 对象ID 29 的动态信息

// 操作 createrequirements 关联需求为空
r($action->getListTest($objectType[0], $storyID[29])) && p() && e('title'); // 测试获取对象类型 story 对象ID 30 的动态信息

// 操作 buildopened 关联发布非空
r($action->getListTest($objectType[6], $hasTwoID[0])) && p() && e('link'); // 测试获取对象类型 release 对象ID 1 的动态信息

// 操作 buildopened 关联发布为空
r($action->getListTest($objectType[6], $hasTwoID[1])) && p() && e('nochanged'); // 测试获取对象类型 release 对象ID 2 的动态信息

// 操作 fromlib 对象类型是 case 关联用例非空
r($action->getListTest($objectType[7], $hasTwoID[0])) && p() && e('link'); // 测试获取对象类型 case 对象ID 1 的动态信息

// 操作 fromlib 对象类型是 case 关联用例为空
r($action->getListTest($objectType[7], $hasTwoID[1])) && p() && e('nochanged'); // 测试获取对象类型 case 对象ID 2 的动态信息

// 操作 fromlib 对象类型是 story
r($action->getListTest($objectType[0], $storyID[30])) && p() && e('nochanged'); // 测试获取对象类型 story 对象ID 31 的动态信息

// 操作 finished 对象类型 todo extra task 存在
r($action->getListTest($objectType[8], $todoID[0])) && p() && e('link'); // 测试获取对象类型 todo 对象ID 1 的动态信息

// 操作 finished 对象类型 todo extra task 不存在
r($action->getListTest($objectType[8], $todoID[1])) && p() && e('link'); // 测试获取对象类型 todo 对象ID 2 的动态信息

// 操作 finished 对象类型 todo extra 为空
r($action->getListTest($objectType[8], $todoID[2])) && p() && e('link'); // 测试获取对象类型 todo 对象ID 3 的动态信息

// 操作 finished 对象类型 story
r($action->getListTest($objectType[0], $storyID[31])) && p() && e('nochanged'); // 测试获取对象类型 story 对象ID 32 的动态信息

// 操作 closed 对象类型 story extra story 存在
r($action->getListTest($objectType[0], $storyID[32])) && p() && e('link'); // 测试获取对象类型 story 对象ID 33 的动态信息

// 操作 closed 对象类型 story extra story 不存在
r($action->getListTest($objectType[0], $storyID[33])) && p() && e('link'); // 测试获取对象类型 story 对象ID 34 的动态信息

// 操作 closed 对象类型 story extra 为空
r($action->getListTest($objectType[0], $storyID[34])) && p() && e('link'); // 测试获取对象类型 story 对象ID 35 的动态信息

// 操作 closed 对象类型 todo
r($action->getListTest($objectType[8], $todoID[3])) && p() && e('nochanged'); // 测试获取对象类型 todo 对象ID 4 的动态信息

// 操作 resolved 对象类型 bug extra bug 存在
r($action->getListTest($objectType[2], $bugID[3])) && p() && e('link'); // 测试获取对象类型 bug 对象ID 4 的动态信息

// 操作 resolved 对象类型 bug extra bug 不存在
r($action->getListTest($objectType[2], $bugID[4])) && p() && e('link'); // 测试获取对象类型 bug 对象ID 5 的动态信息

// 操作 resolved 对象类型 bug extra 为空
r($action->getListTest($objectType[2], $bugID[5])) && p() && e('link'); // 测试获取对象类型 bug 对象ID 6 的动态信息

// 操作 resolved 对象类型 todo
r($action->getListTest($objectType[8], $todoID[4])) && p() && e('nochanged'); // 测试获取对象类型 todo 对象ID 5 的动态信息

// 操作 totask 对象类型 task
r($action->getListTest($objectType[1], $taskID[5])) && p() && e('link'); // 测试获取对象类型 task 对象ID 6 的动态信息

// 操作 linkchildtask 对象类型 task
r($action->getListTest($objectType[1], $taskID[6])) && p() && e('link'); // 测试获取对象类型 task 对象ID 7 的动态信息

// 操作 unlinkchildrentask 对象类型 task
r($action->getListTest($objectType[1], $taskID[7])) && p() && e('link'); // 测试获取对象类型 task 对象ID 8 的动态信息

// 操作 linkparenttask 对象类型 task
r($action->getListTest($objectType[1], $taskID[8])) && p() && e('link'); // 测试获取对象类型 task 对象ID 9 的动态信息

// 操作 unlinkparenttask 对象类型 task
r($action->getListTest($objectType[1], $taskID[9])) && p() && e('link'); // 测试获取对象类型 task 对象ID 10 的动态信息

// 操作 deletechildrentask 对象类型 task
r($action->getListTest($objectType[1], $taskID[10])) && p() && e('link'); // 测试获取对象类型 task 对象ID 11 的动态信息

// 操作 totask 对象类型 feedback
r($action->getListTest($objectType[3], $feedbackID[1])) && p() && e('nochanged'); // 测试获取对象类型 feedback 对象ID 2 的动态信息

// 操作 linkchildtask 对象类型 feedback
r($action->getListTest($objectType[3], $feedbackID[2])) && p() && e('nochanged'); // 测试获取对象类型 feedback 对象ID 3 的动态信息

// 操作 unlinkchildrentask 对象类型 feedback
r($action->getListTest($objectType[3], $feedbackID[3])) && p() && e('nochanged'); // 测试获取对象类型 feedback 对象ID 4 的动态信息

// 操作 linkparenttask 对象类型 feedback
r($action->getListTest($objectType[3], $feedbackID[4])) && p() && e('nochanged'); // 测试获取对象类型 feedback 对象ID 5 的动态信息

// 操作 unlinkparenttask 对象类型 feedback
r($action->getListTest($objectType[3], $feedbackID[5])) && p() && e('nochanged'); // 测试获取对象类型 feedback 对象ID 6 的动态信息

// 操作 deletechildrentask 对象类型 feedback
r($action->getListTest($objectType[3], $feedbackID[6])) && p() && e('nochanged'); // 测试获取对象类型 feedback 对象ID 7 的动态信息

// 操作 linkchildstory
r($action->getListTest($objectType[0], $storyID[35])) && p() && e('link'); // 测试获取对象类型 story 对象ID 36 的动态信息

// 操作 unlinkchildrenstory
r($action->getListTest($objectType[0], $storyID[36])) && p() && e('link'); // 测试获取对象类型 story 对象ID 37 的动态信息

// 操作 linkparentstory
r($action->getListTest($objectType[0], $storyID[37])) && p() && e('link'); // 测试获取对象类型 story 对象ID 38 的动态信息

// 操作 unlinkparentstory
r($action->getListTest($objectType[0], $storyID[38])) && p() && e('link'); // 测试获取对象类型 story 对象ID 39 的动态信息

// 操作 deletechildrenstory
r($action->getListTest($objectType[0], $storyID[39])) && p() && e('link'); // 测试获取对象类型 story 对象ID 40 的动态信息

// 操作 testtaskopened
r($action->getListTest($objectType[9], $testtaskID[0])) && p() && e('link'); // 测试获取对象类型 testtask 对象ID 1 的动态信息

// 操作 testtaskstarted
r($action->getListTest($objectType[9], $testtaskID[1])) && p() && e('link'); // 测试获取对象类型 testtask 对象ID 2 的动态信息

// 操作 testtaskclosed
r($action->getListTest($objectType[9], $testtaskID[2])) && p() && e('link'); // 测试获取对象类型 testtask 对象ID 3 的动态信息

// 开源版 导入资产库相关
// 操作 importfromstorylib
r($action->getListTest($objectType[1], $taskID[11])) && p() && e('nochanged'); // 测试获取对象类型 task 对象ID 12 的动态信息

// 操作 importfromrisklib
r($action->getListTest($objectType[10], $hasOneID[0])) && p() && e('nochanged'); // 测试 开源版 获取对象类型 risk 对象ID 1 的动态信息

// 操作 importfromissuelib
r($action->getListTest($objectType[11], $hasOneID[0])) && p() && e('nochanged'); // 测试 开源版 获取对象类型 isue 对象ID 1 的动态信息

// 操作 importfromopportunitylib
r($action->getListTest($objectType[12], $hasOneID[0])) && p() && e('nochanged'); // 测试 开源版 获取对象类型 opportunity 对象ID 1 的动态信息

// 旗舰版 导入资产库相关
// 操作 importfromstorylib
r($action->getListTest($objectType[1], $taskID[11], 'max')) && p() && e('link'); // 测试获取对象类型 task 对象ID 12 的动态信息

// 操作 importfromrisklib
r($action->getListTest($objectType[10], $hasOneID[0], 'max')) && p() && e('link'); // 测试 开源版 获取对象类型 risk 对象ID 1 的动态信息

// 操作 importfromissuelib
r($action->getListTest($objectType[11], $hasOneID[0], 'max')) && p() && e('link'); // 测试 开源版 获取对象类型 isue 对象ID 1 的动态信息

// 操作 importfromopportunitylib
r($action->getListTest($objectType[12], $hasOneID[0], 'max')) && p() && e('link'); // 测试 开源版 获取对象类型 opportunity 对象ID 1 的动态信息

// 操作 opened 对象类型 execution
r($action->getListTest($objectType[13], $executionID[0])) && p() && e('link'); // 测试获取对象类型 execution 对象ID 1 的动态信息

// 操作 managed 对象类型 execution
r($action->getListTest($objectType[13], $executionID[1])) && p() && e('link'); // 测试获取对象类型 execution 对象ID 2 的动态信息

// 操作 edited 对象类型 execution
r($action->getListTest($objectType[13], $executionID[2])) && p() && e('link'); // 测试获取对象类型 execution 对象ID 3 的动态信息

// 操作 closed 对象类型 execution
r($action->getListTest($objectType[13], $executionID[3])) && p() && e('nochanged'); // 测试获取对象类型 execution 对象ID 4 的动态信息

// 操作 opened 对象类型 project
r($action->getListTest($objectType[14], $projectID[0])) && p() && e('link'); // 测试获取对象类型 project 对象ID 1 的动态信息

// 操作 managed 对象类型 project
r($action->getListTest($objectType[14], $projectID[1])) && p() && e('link'); // 测试获取对象类型 project 对象ID 2 的动态信息

// 操作 edited 对象类型 project
r($action->getListTest($objectType[14], $projectID[2])) && p() && e('link'); // 测试获取对象类型 project 对象ID 3 的动态信息

// 操作 closed 对象类型 project
r($action->getListTest($objectType[14], $projectID[3])) && p() && e('nochanged'); // 测试获取对象类型 project 对象ID 4 的动态信息

// 操作 linkstory
r($action->getListTest($objectType[0], $storyID[40])) && p() && e('link'); // 测试获取对象类型 story 对象ID 41 的动态信息

// 操作 unlinkstory
r($action->getListTest($objectType[0], $storyID[41])) && p() && e('link'); // 测试获取对象类型 story 对象ID 42 的动态信息

// 操作 createchildrenstory
r($action->getListTest($objectType[0], $storyID[42])) && p() && e('link'); // 测试获取对象类型 story 对象ID 43 的动态信息

// 操作 linkbug
r($action->getListTest($objectType[2], $bugID[6])) && p() && e('link'); // 测试获取对象类型 bug 对象ID 7 的动态信息

// 操作 unlinkbug
r($action->getListTest($objectType[2], $bugID[7])) && p() && e('link'); // 测试获取对象类型 bug 对象ID 8 的动态信息

// 操作 svncommited 操作者存在
r($action->getListTest($objectType[1], $taskID[12])) && p() && e('nochanged'); // 测试获取对象类型 task 对象ID 13 的动态信息

// 操作 svncommited 操作者不存在
r($action->getListTest($objectType[1], $taskID[13])) && p() && e('nochanged'); // 测试获取对象类型 task 对象ID 14 的动态信息

// 操作 gitcommited 操作者存在
r($action->getListTest($objectType[1], $taskID[15])) && p() && e('nochanged'); // 测试获取对象类型 task 对象ID 16 的动态信息

// 操作 gitcommited 操作者不存在
r($action->getListTest($objectType[1], $taskID[14])) && p() && e('nochanged'); // 测试获取对象类型 task 对象ID 15 的动态信息
