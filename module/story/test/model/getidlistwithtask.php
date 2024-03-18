#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getIdListWithTask();
cid=0

- 获取执行101下的有关联需求的任务ID列表数量 @1
- 获取执行1下的有关联需求的任务ID列表数量 @0
- 获取执行null下的有关联需求的任务ID列表数量 @0
- 获取执行110下的有关联需求的任务ID列表数量 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('task')->gen(10);

global $tester;
$tester->loadModel('story');
$taslIdList1 = $tester->story->getIdListWithTask(101);
$taslIdList2 = $tester->story->getIdListWithTask(1);
$taslIdList3 = $tester->story->getIdListWithTask(0);
$taslIdList4 = $tester->story->getIdListWithTask(110);

r(count($taslIdList1)) && p() && e('1'); // 获取执行101下的有关联需求的任务ID列表数量
r(count($taslIdList2)) && p() && e('0'); // 获取执行1下的有关联需求的任务ID列表数量
r(count($taslIdList3)) && p() && e('0'); // 获取执行null下的有关联需求的任务ID列表数量
r(count($taslIdList4)) && p() && e('1'); // 获取执行110下的有关联需求的任务ID列表数量
