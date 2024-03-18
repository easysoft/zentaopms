#!/usr/bin/env php
<?php

/**

title=测试 storyModel->close();
cid=0

- 关闭一个用户需求，查看状态
 - 属性status @closed
 - 属性closedReason @done
- 关闭一个软件需求，查看状态
 - 属性status @closed
 - 属性closedReason @willnotdo
- 关闭一个重复了的需求，查看状态
 - 属性status @closed
 - 属性closedReason @duplicate
 - 属性duplicateStory @5
- 关闭一个重复了的需求，但缺少重复的需求的ID，查看状态
 - 属性status @active
 - 属性closedReason @~~
 - 属性duplicateStory @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$stories = zdTable('story');
$stories->status->range('draft,active,changing,active{2}');
$stories->closedReason->range('{5}');
$stories->gen(5);

zdTable('storystage')->gen(5);
zdTable('project')->gen(5);

$postData1 = new stdclass();
$postData1->status = 'closed';
$postData1->closedReason = 'done';

$postData2 = new stdclass();
$postData2->status = 'closed';
$postData2->closedReason = 'willnotdo';

$postData3 = new stdclass();
$postData3->status = 'closed';
$postData3->closedReason   = 'duplicate';
$postData3->duplicateStory = 5;

$postData4 = new stdclass();
$postData4->status = 'closed';
$postData4->closedReason   = 'duplicate';
$postData4->duplicateStory = 0;

$story = new storyTest();
$story1 = $story->closeTest(1, $postData1);
$story2 = $story->closeTest(2, $postData2);
$story3 = $story->closeTest(3, $postData3);
$story4 = $story->closeTest(4, $postData4);

r($story1) && p('status,closedReason')                 && e('closed,done');        // 关闭一个用户需求，查看状态
r($story2) && p('status,closedReason')                 && e('closed,willnotdo');   // 关闭一个软件需求，查看状态
r($story3) && p('status,closedReason,duplicateStory')  && e('closed,duplicate,5'); // 关闭一个重复了的需求，查看状态
r($story4) && p('status,closedReason,duplicateStory')  && e('active,~~,0');        // 关闭一个重复了的需求，但缺少重复的需求的ID，查看状态
