#!/usr/bin/env php
<?php

/**

title=测试 storyModel->batchClose();
cid=18471

- 批量关闭需求，查看需求1被关闭后的字段
 - 第2条的status属性 @closed
 - 第2条的stage属性 @closed
 - 第2条的closedReason属性 @done
 - 第2条的duplicateStory属性 @0
- 批量关闭需求，查看需求2被关闭后的字段
 - 第4条的status属性 @closed
 - 第4条的stage属性 @closed
 - 第4条的closedReason属性 @willnotdo
 - 第4条的duplicateStory属性 @0
- 批量关闭需求，查看需求3被关闭后的字段
 - 第6条的status属性 @closed
 - 第6条的stage属性 @closed
 - 第6条的closedReason属性 @putoff
 - 第6条的duplicateStory属性 @0
- 批量关闭需求，查看需求4被关闭后的字段
 - 第8条的status属性 @closed
 - 第8条的stage属性 @closed
 - 第8条的closedReason属性 @duplicate
 - 第8条的duplicateStory属性 @20
- 批量关闭需求，查看需求5被关闭后的字段
 - 第10条的status属性 @closed
 - 第10条的stage属性 @closed
 - 第10条的closedReason属性 @cancel
 - 第10条的duplicateStory属性 @0
- 批量关闭需求，查看需求6被关闭后的字段
 - 第12条的status属性 @closed
 - 第12条的stage属性 @closed
 - 第12条的closedReason属性 @bydesign
 - 第12条的duplicateStory属性 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

$story = zenData('story');
$story->product->range('1');
$story->version->range('1');
$story->gen(20);

$storyspec = zenData('storyspec');
$storyspec->version->range('1');
$storyspec->gen(12);

zenData('product')->gen(1);

$story = new storyTest();
$storyIdList   = array(2, 4, 6, 8, 10, 12);
$closedReasons = array(2 => 'done', 4 => 'willnotdo', 6 => 'putoff', 8 => 'duplicate', 10 => 'cancel', 12 => 'bydesign');
$duplicateList = array(2 => 0, 4 => 0, 6 => 0, 8 => 20, 10 => 0, 12 => 0);

$storiesToClose = array();
foreach($storyIdList as $storyID)
{
    $storyToClose = new stdclass();
    $storyToClose->status         = 'closed';
    $storyToClose->closedReason   = $closedReasons[$storyID];
    $storyToClose->duplicateStory = $duplicateList[$storyID];

    $storiesToClose[$storyID] = $storyToClose;
}

$stories = $story->batchCloseTest($storiesToClose);
r($stories) && p('2:status,stage,closedReason,duplicateStory')  && e('closed,closed,done,0');       // 批量关闭需求，查看需求1被关闭后的字段
r($stories) && p('4:status,stage,closedReason,duplicateStory')  && e('closed,closed,willnotdo,0');  // 批量关闭需求，查看需求2被关闭后的字段
r($stories) && p('6:status,stage,closedReason,duplicateStory')  && e('closed,closed,putoff,0');     // 批量关闭需求，查看需求3被关闭后的字段
r($stories) && p('8:status,stage,closedReason,duplicateStory')  && e('closed,closed,duplicate,20'); // 批量关闭需求，查看需求4被关闭后的字段
r($stories) && p('10:status,stage,closedReason,duplicateStory') && e('closed,closed,cancel,0');     // 批量关闭需求，查看需求5被关闭后的字段
r($stories) && p('12:status,stage,closedReason,duplicateStory') && e('closed,closed,bydesign,0');   // 批量关闭需求，查看需求6被关闭后的字段
