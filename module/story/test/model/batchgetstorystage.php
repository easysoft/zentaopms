#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$storystage = zdTable('storystage');
$storystage->branch->range('0-100');
$storystage->gen(20);

/**

title=测试 storyModel->batchGetStoryStage();
cid=1
pid=1



*/

global $tester;
$tester->loadModel('story');

$stage = $tester->story->batchGetStoryStage(array(1,2,3));
r($stage[1]) && p('0:stage') && e('wait');
r($stage[2]) && p('1:stage') && e('planned');
r($stage[3]) && p('2:stage') && e('projected');
