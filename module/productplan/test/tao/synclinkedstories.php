#!/usr/bin/env php
<?php
/**

title=测试 productplanTao->syncLinkedStories()
cid=1

- 测试同步需求 1 3 到计划 1，没有新增需求，不删除原有的计划需求 @1,2,3,4

- 测试同步需求 1 9 到计划 1，需求 9 是计划 1 的新增需求，不删除原有的计划需求 @1,2,3,4,9

- 测试同步需求 10 11 到计划 1，需求 10 11 是计划 1 的新增需求，不删除原有的计划需求 @1,2,3,4,9,10,11

- 测试同步需求 1 3 到计划 1，没有新增需求，删除原有的计划需求 @1,3

- 测试同步需求 1 9 到计划 1，需求 9 是计划 1 的新增需求，删除原有的计划需求 @1,9

- 测试同步需求 10 11 到计划 1，需求 10 11 是计划 1 的新增需求，删除原有的计划需求 @10,11

- 测试同步需求 2 5 12 到计划 4，需求 2 12 是计划 4 的新增需求，不删除原有的计划需求 @2,5,6,7,8,12

- 测试同步需求 6 13 到计划 4，需求 13 计划 43 的新增需求，不删除原有的计划需求 @2,5,6,7,8,12,13

- 测试同步需求 14 到计划 4，需求 14 计划 43 的新增需求，不删除原有的计划需求 @2,5,6,7,8,12,13,14

- 测试同步需求 2 5 12 到计划 4，需求 2 12 是计划 4 的新增需求，删除原有的计划需求 @2,5,12

- 测试同步需求 6 13 到计划 4，需求 6 13 计划 43 的新增需求，删除原有的计划需求 @6,13

- 测试同步需求 14 到计划 4，需求 14 计划 43 的新增需求，删除原有的计划需求 @14

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('productplan')->gen(3);
zdTable('story')->gen(20);
zdTable('planstory')->gen(8);

$productplan = new productPlan('admin');

$planID      = array(1, 4);
$storyIdList = array(array(1, 3), array(1, 9), array(10, 11), array(2, 5, 12), array(6, 13), array(14));
$deleteOld   = array(false, true);

r($productplan->syncLinkedStoriesTest($planID[0], $storyIdList[0], $deleteOld[0])) && p() && e('1,2,3,4');            // 测试同步需求 1 3 到计划 1，没有新增需求，不删除原有的计划需求
r($productplan->syncLinkedStoriesTest($planID[0], $storyIdList[1], $deleteOld[0])) && p() && e('1,2,3,4,9');          // 测试同步需求 1 9 到计划 1，需求 9 是计划 1 的新增需求，不删除原有的计划需求
r($productplan->syncLinkedStoriesTest($planID[0], $storyIdList[2], $deleteOld[0])) && p() && e('1,2,3,4,9,10,11');    // 测试同步需求 10 11 到计划 1，需求 10 11 是计划 1 的新增需求，不删除原有的计划需求
r($productplan->syncLinkedStoriesTest($planID[0], $storyIdList[0], $deleteOld[1])) && p() && e('1,3');                // 测试同步需求 1 3 到计划 1，没有新增需求，删除原有的计划需求
r($productplan->syncLinkedStoriesTest($planID[0], $storyIdList[1], $deleteOld[1])) && p() && e('1,9');                // 测试同步需求 1 9 到计划 1，需求 9 是计划 1 的新增需求，删除原有的计划需求
r($productplan->syncLinkedStoriesTest($planID[0], $storyIdList[2], $deleteOld[1])) && p() && e('10,11');              // 测试同步需求 10 11 到计划 1，需求 10 11 是计划 1 的新增需求，删除原有的计划需求
r($productplan->syncLinkedStoriesTest($planID[1], $storyIdList[3], $deleteOld[0])) && p() && e('2,5,6,7,8,12');       // 测试同步需求 2 5 12 到计划 4，需求 2 12 是计划 4 的新增需求，不删除原有的计划需求
r($productplan->syncLinkedStoriesTest($planID[1], $storyIdList[4], $deleteOld[0])) && p() && e('2,5,6,7,8,12,13');    // 测试同步需求 6 13 到计划 4，需求 13 计划 43 的新增需求，不删除原有的计划需求
r($productplan->syncLinkedStoriesTest($planID[1], $storyIdList[5], $deleteOld[0])) && p() && e('2,5,6,7,8,12,13,14'); // 测试同步需求 14 到计划 4，需求 14 计划 43 的新增需求，不删除原有的计划需求
r($productplan->syncLinkedStoriesTest($planID[1], $storyIdList[3], $deleteOld[1])) && p() && e('2,5,12');             // 测试同步需求 2 5 12 到计划 4，需求 2 12 是计划 4 的新增需求，删除原有的计划需求
r($productplan->syncLinkedStoriesTest($planID[1], $storyIdList[4], $deleteOld[1])) && p() && e('6,13');               // 测试同步需求 6 13 到计划 4，需求 6 13 计划 43 的新增需求，删除原有的计划需求
r($productplan->syncLinkedStoriesTest($planID[1], $storyIdList[5], $deleteOld[1])) && p() && e('14');                 // 测试同步需求 14 到计划 4，需求 14 计划 43 的新增需求，删除原有的计划需求
