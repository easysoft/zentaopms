#!/usr/bin/env php
<?php

/**

title=测试 storyModel->computeStagesByRelease();
cid=0

- 不传入任何数据。 @0
- 只传入需求 ID。 @0
- 已经发布的需求。
 -  @released
 - 属性1 @released
 - 属性2 @released

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$release = zdTable('release');
$release->stories->range('2');
$release->branch->range('0,1,2');
$release->gen(3);

global $tester;
$storyModel = $tester->loadModel('story');

r($storyModel->computeStagesByRelease(0, array())) && p()        && e('0');                          //不传入任何数据。
r($storyModel->computeStagesByRelease(1, array())) && p()        && e('0');                          //只传入需求 ID。
r($storyModel->computeStagesByRelease(2, array())) && p('0,1,2') && e('released,released,released'); //已经发布的需求。
