#!/usr/bin/env php
<?php

/**

title=taskModel->getUnclosedTasksByExecution();
cid=0

- 查询单个执行 101 未关闭的任务 @开发任务11
- 查询单个执行 102 未关闭的任务 @开发任务12
- 查询单个执行 106 未关闭的任务 @0
- 查询 执行 101 未关闭的任务id和执行id @1,101

- 查询 执行 102 未关闭的任务id和执行id @2,102

- 查询 执行 106 未关闭的任务id和执行id @0
- 查询 执行 101 102 未关闭的任务id和执行id @1,101;2,102

- 查询 执行 101 106 未关闭的任务id和执行id @1,101

- 查询 执行 110 未关闭的任务id和执行id @0
- 查询 执行 110 未关闭的任务id和执行id @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
zenData('user')->gen(5);
su('admin');
