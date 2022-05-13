#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getStorysEstimateHours();
cid=1
pid=1

获取需求 1 的预计工时 >> 1
获取需求 2 的预计工时 >> 1
获取需求 3 的预计工时 >> 1
获取需求 4 的预计工时 >> 1
获取需求 5 的预计工时 >> 1

*/

$storyID = array(1, 2, 3, 4, 5);

$block = new blockTest();

r($block->getStorysEstimateHoursTest($storyID[0])) && p('estimate') && e('1'); // 获取需求 1 的预计工时
r($block->getStorysEstimateHoursTest($storyID[1])) && p('estimate') && e('1'); // 获取需求 2 的预计工时
r($block->getStorysEstimateHoursTest($storyID[2])) && p('estimate') && e('1'); // 获取需求 3 的预计工时
r($block->getStorysEstimateHoursTest($storyID[3])) && p('estimate') && e('1'); // 获取需求 4 的预计工时
r($block->getStorysEstimateHoursTest($storyID[4])) && p('estimate') && e('1'); // 获取需求 5 的预计工时