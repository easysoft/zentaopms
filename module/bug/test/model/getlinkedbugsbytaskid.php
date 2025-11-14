#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->loadYaml('bug_linktask')->gen(6);
zenData('task')->loadYaml('task')->gen(5);

/**

title=bugModel->getLinkedBugsByTaskID();
cid=15382

- 测试获取任务 1 的相关 Bug @1:BUG1,5:BUG5

- 测试获取任务 2 的相关 Bug @2:BUG2,6:BUG6

- 测试获取任务 3 的相关 Bug @3:BUG3
- 测试获取任务 4 的相关 Bug @0
- 测试获取任务 100 的相关 Bug @0

*/

$taskIdList = array(1, 2, 3, 4, 100);

$bug=new bugTest();

r($bug->getLinkedBugsByTaskIDTest($taskIdList[0])) && p() && e('1:BUG1,5:BUG5'); // 测试获取任务 1 的相关 Bug
r($bug->getLinkedBugsByTaskIDTest($taskIdList[1])) && p() && e('2:BUG2,6:BUG6'); // 测试获取任务 2 的相关 Bug
r($bug->getLinkedBugsByTaskIDTest($taskIdList[2])) && p() && e('3:BUG3');        // 测试获取任务 3 的相关 Bug
r($bug->getLinkedBugsByTaskIDTest($taskIdList[3])) && p() && e('0');             // 测试获取任务 4 的相关 Bug
r($bug->getLinkedBugsByTaskIDTest($taskIdList[4])) && p() && e('0');             // 测试获取任务 100 的相关 Bug
