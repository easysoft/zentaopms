#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getIdListWithTask();
cid=1
pid=1

获取执行101下的有关联需求的任务ID列表数量 >> 1
获取执行1下的有关联需求的任务ID列表数量 >> 0
获取执行null下的有关联需求的任务ID列表数量 >> 0
获取执行110下的有关联需求的任务ID列表数量 >> 1

*/

global $tester;
$tester->loadModel('story');
$taslIdList1 = $tester->story->getIdListWithTask(101);
$taslIdList2 = $tester->story->getIdListWithTask(1);
$taslIdList3 = $tester->story->getIdListWithTask(null);
$taslIdList4 = $tester->story->getIdListWithTask(110);

r(count($taslIdList1)) && p() && e('1'); // 获取执行101下的有关联需求的任务ID列表数量
r(count($taslIdList2)) && p() && e('0'); // 获取执行1下的有关联需求的任务ID列表数量
r(count($taslIdList3)) && p() && e('0'); // 获取执行null下的有关联需求的任务ID列表数量
r(count($taslIdList4)) && p() && e('1'); // 获取执行110下的有关联需求的任务ID列表数量