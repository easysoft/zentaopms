#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('task')->loadYaml('task')->gen(12);

/**

title=taskModel->getListByStory();
timeout=0
cid=18813

- 获取未关联需求的任务第5条的name属性 @开发任务15
- 获取关联需求ID=1的任务第1条的name属性 @开发任务11
- 获取关联需求ID=2的任务第3条的name属性 @开发任务13
- 获取关联需求ID=3的任务 @0
- 获取未关联需求的任务数量 @4
- 获取关联需求ID=1的任务数量 @2
- 获取关联需求ID=2的任务数量 @2
- 获取关联需求ID=3的任务数量 @0
- 获取未关联需求并且执行ID=3的任务 @0
- 获取关联需求ID=1并且执行ID=3的任务第1条的name属性 @开发任务11
- 获取关联需求ID=2并且执行ID=3的任务第3条的name属性 @开发任务13
- 获取关联需求ID=3并且执行ID=3的任务 @0
- 获取未关联需求并且执行ID=3的任务数量 @0
- 获取关联需求ID=1并且执行ID=3的的任务数量 @2
- 获取关联需求ID=2并且执行ID=3的的任务数量 @1
- 获取关联需求ID=3并且执行ID=3的的任务数量 @0
- 获取未关联需求并且执行ID=4的任务第5条的name属性 @开发任务15
- 获取关联需求ID=1并且执行ID=4的任务 @0
- 获取关联需求ID=2并且执行ID=4的任务第4条的name属性 @开发任务14
- 获取关联需求ID=3并且执行ID=4的任务 @0
- 获取未关联需求并且执行ID=4的任务数量 @2
- 获取关联需求ID=1并且执行ID=4的的任务数量 @0
- 获取关联需求ID=2并且执行ID=4的的任务数量 @1
- 获取关联需求ID=3并且执行ID=4的的任务数量 @0
- 获取未关联需求并且执行ID=6的任务 @0
- 获取关联需求ID=1并且执行ID=6的任务 @0
- 获取关联需求ID=2并且执行ID=6的任务 @0
- 获取关联需求ID=3并且执行ID=6的任务 @0
- 获取未关联需求并且执行ID=6的任务数量 @0
- 获取关联需求ID=1并且执行ID=6的的任务数量 @0
- 获取关联需求ID=2并且执行ID=6的的任务数量 @0
- 获取关联需求ID=3并且执行ID=6的的任务数量 @0
- 获取未关联需求并且执行ID=3、项目ID=1的任务 @0
- 获取关联需求ID=1并且执行ID=3、项目ID=1的任务第2条的name属性 @开发任务12
- 获取关联需求ID=2并且执行ID=3、项目ID=1的任务第3条的name属性 @开发任务13
- 获取关联需求ID=3并且执行ID=3、项目ID=1的任务 @0
- 获取未关联需求并且执行ID=3、项目ID=1的任务数量 @0
- 获取关联需求ID=1并且执行ID=3、项目ID=1的的任务数量 @2
- 获取关联需求ID=2并且执行ID=3、项目ID=1的的任务数量 @1
- 获取关联需求ID=3并且执行ID=3、项目ID=1的的任务数量 @0
- 获取未关联需求并且执行ID=3、项目ID=2的任务 @0
- 获取关联需求ID=1并且执行ID=3、项目ID=2的任务 @0
- 获取关联需求ID=2并且执行ID=3、项目ID=2的任务 @0
- 获取关联需求ID=3并且执行ID=3、项目ID=2的任务 @0
- 获取未关联需求并且执行ID=3、项目ID=2的任务数量 @0
- 获取关联需求ID=1并且执行ID=3、项目ID=2的的任务数量 @0
- 获取关联需求ID=2并且执行ID=3、项目ID=2的的任务数量 @0
- 获取关联需求ID=3并且执行ID=3、项目ID=2的的任务数量 @0

*/

$storyIdList     = array(0, 1, 2, 3);
$executionIdList = array(3, 4, 6);
$projectIdList   = array(1, 2);

$task = new taskModelTest();
r($task->getListByStoryTest($storyIdList[0]))        && p('5:name') && e('开发任务15'); // 获取未关联需求的任务
r($task->getListByStoryTest($storyIdList[1]))        && p('1:name') && e('开发任务11'); // 获取关联需求ID=1的任务
r($task->getListByStoryTest($storyIdList[2]))        && p('3:name') && e('开发任务13'); // 获取关联需求ID=2的任务
r($task->getListByStoryTest($storyIdList[3]))        && p()         && e('0');          // 获取关联需求ID=3的任务
r(count($task->getListByStoryTest($storyIdList[0]))) && p()         && e('4');          // 获取未关联需求的任务数量
r(count($task->getListByStoryTest($storyIdList[1]))) && p()         && e('2');          // 获取关联需求ID=1的任务数量
r(count($task->getListByStoryTest($storyIdList[2]))) && p()         && e('2');          // 获取关联需求ID=2的任务数量
r(count($task->getListByStoryTest($storyIdList[3]))) && p()         && e('0');          // 获取关联需求ID=3的任务数量

r($task->getListByStoryTest($storyIdList[0], $executionIdList[0]))        && p()         && e('0');          // 获取未关联需求并且执行ID=3的任务
r($task->getListByStoryTest($storyIdList[1], $executionIdList[0]))        && p('1:name') && e('开发任务11'); // 获取关联需求ID=1并且执行ID=3的任务
r($task->getListByStoryTest($storyIdList[2], $executionIdList[0]))        && p('3:name') && e('开发任务13'); // 获取关联需求ID=2并且执行ID=3的任务
r($task->getListByStoryTest($storyIdList[3], $executionIdList[0]))        && p()         && e('0');          // 获取关联需求ID=3并且执行ID=3的任务
r(count($task->getListByStoryTest($storyIdList[0], $executionIdList[0]))) && p()         && e('0');          // 获取未关联需求并且执行ID=3的任务数量
r(count($task->getListByStoryTest($storyIdList[1], $executionIdList[0]))) && p()         && e('2');          // 获取关联需求ID=1并且执行ID=3的的任务数量
r(count($task->getListByStoryTest($storyIdList[2], $executionIdList[0]))) && p()         && e('1');          // 获取关联需求ID=2并且执行ID=3的的任务数量
r(count($task->getListByStoryTest($storyIdList[3], $executionIdList[0]))) && p()         && e('0');          // 获取关联需求ID=3并且执行ID=3的的任务数量

r($task->getListByStoryTest($storyIdList[0], $executionIdList[1]))        && p('5:name') && e('开发任务15'); // 获取未关联需求并且执行ID=4的任务
r($task->getListByStoryTest($storyIdList[1], $executionIdList[1]))        && p()         && e('0');          // 获取关联需求ID=1并且执行ID=4的任务
r($task->getListByStoryTest($storyIdList[2], $executionIdList[1]))        && p('4:name') && e('开发任务14'); // 获取关联需求ID=2并且执行ID=4的任务
r($task->getListByStoryTest($storyIdList[3], $executionIdList[1]))        && p()         && e('0');          // 获取关联需求ID=3并且执行ID=4的任务
r(count($task->getListByStoryTest($storyIdList[0], $executionIdList[1]))) && p()         && e('2');          // 获取未关联需求并且执行ID=4的任务数量
r(count($task->getListByStoryTest($storyIdList[1], $executionIdList[1]))) && p()         && e('0');          // 获取关联需求ID=1并且执行ID=4的的任务数量
r(count($task->getListByStoryTest($storyIdList[2], $executionIdList[1]))) && p()         && e('1');          // 获取关联需求ID=2并且执行ID=4的的任务数量
r(count($task->getListByStoryTest($storyIdList[3], $executionIdList[1]))) && p()         && e('0');          // 获取关联需求ID=3并且执行ID=4的的任务数量

r($task->getListByStoryTest($storyIdList[0], $executionIdList[2]))        && p() && e('0'); // 获取未关联需求并且执行ID=6的任务
r($task->getListByStoryTest($storyIdList[1], $executionIdList[2]))        && p() && e('0'); // 获取关联需求ID=1并且执行ID=6的任务
r($task->getListByStoryTest($storyIdList[2], $executionIdList[2]))        && p() && e('0'); // 获取关联需求ID=2并且执行ID=6的任务
r($task->getListByStoryTest($storyIdList[3], $executionIdList[2]))        && p() && e('0'); // 获取关联需求ID=3并且执行ID=6的任务
r(count($task->getListByStoryTest($storyIdList[0], $executionIdList[2]))) && p() && e('0'); // 获取未关联需求并且执行ID=6的任务数量
r(count($task->getListByStoryTest($storyIdList[1], $executionIdList[2]))) && p() && e('0'); // 获取关联需求ID=1并且执行ID=6的的任务数量
r(count($task->getListByStoryTest($storyIdList[2], $executionIdList[2]))) && p() && e('0'); // 获取关联需求ID=2并且执行ID=6的的任务数量
r(count($task->getListByStoryTest($storyIdList[3], $executionIdList[2]))) && p() && e('0'); // 获取关联需求ID=3并且执行ID=6的的任务数量

r($task->getListByStoryTest($storyIdList[0], $executionIdList[0], $projectIdList[0]))        && p()         && e('0');          // 获取未关联需求并且执行ID=3、项目ID=1的任务
r($task->getListByStoryTest($storyIdList[1], $executionIdList[0], $projectIdList[0]))        && p('2:name') && e('开发任务12'); // 获取关联需求ID=1并且执行ID=3、项目ID=1的任务
r($task->getListByStoryTest($storyIdList[2], $executionIdList[0], $projectIdList[0]))        && p('3:name') && e('开发任务13'); // 获取关联需求ID=2并且执行ID=3、项目ID=1的任务
r($task->getListByStoryTest($storyIdList[3], $executionIdList[0], $projectIdList[0]))        && p()         && e('0');          // 获取关联需求ID=3并且执行ID=3、项目ID=1的任务
r(count($task->getListByStoryTest($storyIdList[0], $executionIdList[0], $projectIdList[0]))) && p()         && e('0');          // 获取未关联需求并且执行ID=3、项目ID=1的任务数量
r(count($task->getListByStoryTest($storyIdList[1], $executionIdList[0], $projectIdList[0]))) && p()         && e('2');          // 获取关联需求ID=1并且执行ID=3、项目ID=1的的任务数量
r(count($task->getListByStoryTest($storyIdList[2], $executionIdList[0], $projectIdList[0]))) && p()         && e('1');          // 获取关联需求ID=2并且执行ID=3、项目ID=1的的任务数量
r(count($task->getListByStoryTest($storyIdList[3], $executionIdList[0], $projectIdList[0]))) && p()         && e('0');          // 获取关联需求ID=3并且执行ID=3、项目ID=1的的任务数量

r($task->getListByStoryTest($storyIdList[0], $executionIdList[0], $projectIdList[1]))        && p() && e('0'); // 获取未关联需求并且执行ID=3、项目ID=2的任务
r($task->getListByStoryTest($storyIdList[1], $executionIdList[0], $projectIdList[1]))        && p() && e('0'); // 获取关联需求ID=1并且执行ID=3、项目ID=2的任务
r($task->getListByStoryTest($storyIdList[2], $executionIdList[0], $projectIdList[1]))        && p() && e('0'); // 获取关联需求ID=2并且执行ID=3、项目ID=2的任务
r($task->getListByStoryTest($storyIdList[3], $executionIdList[0], $projectIdList[1]))        && p() && e('0'); // 获取关联需求ID=3并且执行ID=3、项目ID=2的任务
r(count($task->getListByStoryTest($storyIdList[0], $executionIdList[0], $projectIdList[1]))) && p() && e('0'); // 获取未关联需求并且执行ID=3、项目ID=2的任务数量
r(count($task->getListByStoryTest($storyIdList[1], $executionIdList[0], $projectIdList[1]))) && p() && e('0'); // 获取关联需求ID=1并且执行ID=3、项目ID=2的的任务数量
r(count($task->getListByStoryTest($storyIdList[2], $executionIdList[0], $projectIdList[1]))) && p() && e('0'); // 获取关联需求ID=2并且执行ID=3、项目ID=2的的任务数量
r(count($task->getListByStoryTest($storyIdList[3], $executionIdList[0], $projectIdList[1]))) && p() && e('0'); // 获取关联需求ID=3并且执行ID=3、项目ID=2的的任务数量