#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

/**

title=taskTao->updateRelation();
cid=0

- 都传入空参数 @null
- childID传入空参数 @null
- 传入正常参数 @1
- 修改关联父任务 @3
- 解除关联 @null

*/

zenData('relation')->gen(0);

$task = new taskTest();

r($task->updateRelationTest(0, 0)) && p() && e('null');  //都传入空参数
r($task->updateRelationTest(0, 1)) && p() && e('null');  //childID传入空参数
r($task->updateRelationTest(2, 1)) && p() && e('1');     //传入正常参数
r($task->updateRelationTest(2, 3)) && p() && e('3');     //修改关联父任务
r($task->updateRelationTest(2, 0)) && p() && e('null');  //解除关联
