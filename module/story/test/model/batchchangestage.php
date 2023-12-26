#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$story = zdTable('story');
$story->version->range(1);
$story->gen(20);
zdTable('storyspec')->gen(20);
zdTable('storystage')->gen(20);
zdTable('product')->gen(20);

/**

title=测试 storyModel->batchChangeStage();
cid=1
pid=1

*/

$story       = new storyTest();
$storyIdList = array(1, 2, 6, 10, 14);
$result      = $story->batchChangeStageTest($storyIdList, 'developing');

r(count($result))  && p()              && e('5');             // 批量修改6个需求的阶段，查看被修改阶段的需求数量
r($result)         && p('0:id,stage')  && e('1,~~');          // 批量修改6个需求的阶段，查看需求101修改后的阶段
r($result)         && p('1:id,stage')  && e('2,developing');  // 批量修改6个需求的阶段，查看需求102修改后的阶段
r($result)         && p('2:id,stage')  && e('6,developing');  // 批量修改6个需求的阶段，查看需求104修改后的阶段
r($result)         && p('3:id,stage')  && e('10,developing'); // 批量修改6个需求的阶段，查看需求110修改后的阶段
r($result)         && p('4:id,stage')  && e('14,developing'); // 批量修改6个需求的阶段，查看需求114修改后的阶段
