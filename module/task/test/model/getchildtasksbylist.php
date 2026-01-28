#!/usr/bin/env php
<?php

/**

title=taskModel->getChildTasksByList();
cid=18792

- 查询任务 1 的子任务和未关联需求的子任务属性1 @childTasks: 6,7; nonStoryChildTasks: 7;
- 查询任务 2 的子任务和未关联需求的子任务属性2 @childTasks: 8,9;
- 查询任务 1 和 2 的子任务和未关联需求的子任务 1属性1 @childTasks: 6,7; nonStoryChildTasks: 7;
- 查询任务 1 和 2 的子任务和未关联需求的子任务 2属性2 @childTasks: 8,9;
- 查询任务 3 的子任务和未关联需求的子任务属性3 @childTasks: 10;
- 查询任务 11 的子任务和未关联需求的子任务 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

zenData('task')->loadYaml('task')->gen(10);

$taskIdList = array(array(1), array(2), array(1, 2), array(3), array(11));

$task = new taskModelTest();
r($task->getChildTasksByListTest($taskIdList[0])) && p('1', '|') && e('childTasks: 6,7; nonStoryChildTasks: 7;'); // 查询任务 1 的子任务和未关联需求的子任务
r($task->getChildTasksByListTest($taskIdList[1])) && p('2', '|') && e('childTasks: 8,9;');                        // 查询任务 2 的子任务和未关联需求的子任务
r($task->getChildTasksByListTest($taskIdList[2])) && p('1', '|') && e('childTasks: 6,7; nonStoryChildTasks: 7;'); // 查询任务 1 和 2 的子任务和未关联需求的子任务 1
r($task->getChildTasksByListTest($taskIdList[2])) && p('2', '|') && e('childTasks: 8,9;');                        // 查询任务 1 和 2 的子任务和未关联需求的子任务 2
r($task->getChildTasksByListTest($taskIdList[3])) && p('3', '|') && e('childTasks: 10;');                         // 查询任务 3 的子任务和未关联需求的子任务
r($task->getChildTasksByListTest($taskIdList[4])) && p()    && e('0');                                       // 查询任务 11 的子任务和未关联需求的子任务
