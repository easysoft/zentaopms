#!/usr/bin/env php
<?php

/**

title=测试 storyModel->reportCondition();
timeout=0
cid=18581

- 执行story模块的reportCondition方法，参数是'story'  @1=1
- 执行story模块的reportCondition方法，参数是'story'  @`title` like '%aa%'
- 执行story模块的reportCondition方法，参数是'story'  @id in (SELECT t1.id FROM `zt_story` AS t1 WHERE t1.`title` like '%aa%')
- 执行story模块的reportCondition方法，参数是'story'  @`title` like '%aa%'
- 执行story模块的reportCondition方法，参数是'story'  @id in (SELECT t1.id FROM `zt_story` AS t1 WHERE t1.`title` like '%aa%')

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
global $tester;
$tester->loadModel('story');

r($tester->story->reportCondition('story')) && p() && e('1=1');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`title` like '%aa%'";
r($tester->story->reportCondition('story')) && p() && e("`title` like '%aa%'");

$_SESSION['storyOnlyCondition']  = false;
$_SESSION['storyQueryCondition'] = "SELECT * FROM `zt_story` AS t1 WHERE t1.`title` like '%aa%'";
r($tester->story->reportCondition('story')) && p() && e("id in (SELECT t1.id FROM `zt_story` AS t1 WHERE t1.`title` like '%aa%')");

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`title` like '%aa%'";
r($tester->story->reportCondition('story')) && p() && e("`title` like '%aa%'");

$_SESSION['storyOnlyCondition']  = false;
$_SESSION['storyQueryCondition'] = "SELECT * FROM `zt_story` AS t1 WHERE t1.`title` like '%aa%'";
r($tester->story->reportCondition('story')) && p() && e("id in (SELECT t1.id FROM `zt_story` AS t1 WHERE t1.`title` like '%aa%')");
