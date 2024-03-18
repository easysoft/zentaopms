#!/usr/bin/env php
<?php

/**

title=测试 storyModel->batchGetStoryStage();
cid=0

- 执行$stage[1]第0条的stage属性 @wait
- 执行$stage[2]第1条的stage属性 @planned
- 执行$stage[3]第2条的stage属性 @projected

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$storystage = zdTable('storystage');
$storystage->branch->range('0-100');
$storystage->gen(20);

global $tester;
$tester->loadModel('story');

$stage = $tester->story->batchGetStoryStage(array(1,2,3));
r($stage[1]) && p('0:stage') && e('wait');
r($stage[2]) && p('1:stage') && e('planned');
r($stage[3]) && p('2:stage') && e('projected');
