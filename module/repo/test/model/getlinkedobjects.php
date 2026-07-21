#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel::getLinkedObjects();
timeout=0
cid=0

- 解析单个任务关键字 Task#8 @8
- 解析多个任务关键字 Task#1,8,12 @1|8|12
- 解析单个 Bug 关键字 Bug#3 @3
- 解析需求关键字 Story#1,2 @1|2
- 三类混合 Task#1 Bug#2 Story#3 各取 @1 @2 @3

*/

$tester = new repoModelTest();

r($tester->getLinkedObjectsTest('Finish Task#8.'))                  && p('tasks')              && e('8');                       // 步骤1：单任务
r($tester->getLinkedObjectsTest('Finish Task#1,8,12.'))             && p('tasks')              && e('1|8|12');                  // 步骤2：多任务
r($tester->getLinkedObjectsTest('Fix Bug#3'))                       && p('bugs')               && e('3');                       // 步骤3：单 bug
r($tester->getLinkedObjectsTest('Story#1,2'))                       && p('stories')            && e('1|2');                     // 步骤4：多需求
r($tester->getLinkedObjectsTest('Task#1 Bug#2 Story#3'))            && p('tasks;bugs;stories') && e('1;2;3');                   // 步骤5：三类混合
