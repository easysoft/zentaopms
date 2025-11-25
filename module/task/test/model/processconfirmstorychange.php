#!/usr/bin/env php
<?php

/**

title=taskModel->processConfirmStoryChange();
timeout=0
cid=18836

- 测试普通任务没有操作按钮时，处理确认变更按钮 @0
- 测试指派给admin的普通任务处理确认变更按钮
 - 第0条的name属性 @confirmStoryChange
 - 第0条的disabled属性 @~~
- 测试指派给admin的父任务处理确认变更按钮
 - 第0条的name属性 @confirmStoryChange
 - 第0条的disabled属性 @~~
- 测试指派给admin的子任务处理确认变更按钮
 - 第0条的name属性 @confirmStoryChange
 - 第0条的disabled属性 @~~
- 测试指派给admin的串行任务处理确认变更按钮
 - 第0条的name属性 @confirmStoryChange
 - 第0条的disabled属性 @~~
- 测试指派给admin的并行任务处理确认变更按钮
 - 第0条的name属性 @confirmStoryChange
 - 第0条的disabled属性 @~~
- 测试普通任务没有操作按钮时，处理确认变更按钮 @0
- 测试指派给admin的普通任务处理确认变更按钮
 - 第0条的name属性 @confirmStoryChange
 - 第0条的disabled属性 @1
- 测试指派给admin的父任务处理确认变更按钮
 - 第0条的name属性 @confirmStoryChange
 - 第0条的disabled属性 @1
- 测试指派给admin的子任务处理确认变更按钮
 - 第0条的name属性 @confirmStoryChange
 - 第0条的disabled属性 @1
- 测试指派给admin的串行任务处理确认变更按钮
 - 第0条的name属性 @confirmStoryChange
 - 第0条的disabled属性 @~~
- 测试指派给admin的并行任务处理确认变更按钮
 - 第0条的name属性 @confirmStoryChange
 - 第0条的disabled属性 @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('project')->loadYaml('project')->gen(3);
zenData('taskteam')->loadYaml('taskteam')->gen(6);
zenData('story')->loadYaml('story')->gen(5);
zenData('task')->loadYaml('task')->gen(5);
zenData('user')->gen(5);
su('admin');

$taskIdList = range(1, 5);

$taskTester = new taskTest();
r($taskTester->processConfirmStoryChangeTest($taskIdList[0]))       && p()                  && e('0');                     // 测试普通任务没有操作按钮时，处理确认变更按钮
r($taskTester->processConfirmStoryChangeTest($taskIdList[0], true)) && p('0:name,disabled') && e('confirmStoryChange,~~'); // 测试指派给admin的普通任务处理确认变更按钮
r($taskTester->processConfirmStoryChangeTest($taskIdList[1], true)) && p('0:name,disabled') && e('confirmStoryChange,~~'); // 测试指派给admin的父任务处理确认变更按钮
r($taskTester->processConfirmStoryChangeTest($taskIdList[2], true)) && p('0:name,disabled') && e('confirmStoryChange,~~'); // 测试指派给admin的子任务处理确认变更按钮
r($taskTester->processConfirmStoryChangeTest($taskIdList[3], true)) && p('0:name,disabled') && e('confirmStoryChange,~~'); // 测试指派给admin的串行任务处理确认变更按钮
r($taskTester->processConfirmStoryChangeTest($taskIdList[4], true)) && p('0:name,disabled') && e('confirmStoryChange,~~'); // 测试指派给admin的并行任务处理确认变更按钮

su('user1');
r($taskTester->processConfirmStoryChangeTest($taskIdList[0]))       && p()                  && e('0');                     // 测试普通任务没有操作按钮时，处理确认变更按钮
r($taskTester->processConfirmStoryChangeTest($taskIdList[0], true)) && p('0:name,disabled') && e('confirmStoryChange,1');  // 测试指派给admin的普通任务处理确认变更按钮
r($taskTester->processConfirmStoryChangeTest($taskIdList[1], true)) && p('0:name,disabled') && e('confirmStoryChange,1');  // 测试指派给admin的父任务处理确认变更按钮
r($taskTester->processConfirmStoryChangeTest($taskIdList[2], true)) && p('0:name,disabled') && e('confirmStoryChange,1');  // 测试指派给admin的子任务处理确认变更按钮
r($taskTester->processConfirmStoryChangeTest($taskIdList[3], true)) && p('0:name,disabled') && e('confirmStoryChange,~~'); // 测试指派给admin的串行任务处理确认变更按钮
r($taskTester->processConfirmStoryChangeTest($taskIdList[4], true)) && p('0:name,disabled') && e('confirmStoryChange,~~'); // 测试指派给admin的并行任务处理确认变更按钮
