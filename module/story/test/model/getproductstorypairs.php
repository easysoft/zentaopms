#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$story = zdTable('story');
$story->estimate->range('1-4');
$story->gen(20);

zdTable('product')->gen(20);

su('admin');

/**

title=测试 storyModel->getProductStoryPairs();
cid=1
pid=1

获取产品1下的所有软件需求数量 >> 3
获取产品1下的所有用户需求数量 >> 4
获取产品2可关联的需求数量 >> 2
查看通过产品1获取的软件需求的名称字段 >> 4:软件需求4 (优先级:4,预计工时:0),2:软件需求2 (优先级:2,预计工时:20)
查看通过产品1获取的用户需求的键值对详情 >> 1:用户需求1,2:用户需求2,更多...
查看通过产品3获取的软件需求的键值对详情 >> 10:软件需求10 (优先级:2,预计工时:9)

*/

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getProductStoryPairs(1);
$stories2 = $tester->story->getProductStoryPairs(3, 0, 1830, 'all', 'id_asc', 0, 'full', 'story', true);

r(count($stories1)) && p()               && e('2'); // 获取产品1下的所有软件需求数量
r(count($stories2)) && p()               && e('1'); // 获取产品1下的所有用户需求数量
r($stories1)        && p('4|2', '|')     && e('4:软件需求4 (优先级:4,预计工时:4)|2:软件需求2 (优先级:2,预计工时:2)'); // 查看通过产品1获取的软件需求的名称字段
r($stories2)        && p('10', '|')      && e('10:软件需求10 (优先级:2,预计工时:2)'); // 查看通过产品3获取的软件需求的键值对详情
