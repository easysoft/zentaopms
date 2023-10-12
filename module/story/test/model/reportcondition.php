#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 storyModel->reportCondition();
cid=1
pid=1

*/
global $tester;
$tester->loadModel('story');

r($tester->story->reportCondition()) && p() && e('1=1');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`title` like '%aa%'";
r($tester->story->reportCondition()) && p() && e("`title` like '%aa%'");

$_SESSION['storyOnlyCondition']  = false;
$_SESSION['storyQueryCondition'] = "SELECT * FROM `zt_story` AS t1 WHERE t1.`title` like '%aa%'";
r($tester->story->reportCondition()) && p() && e("id in (SELECT t1.id FROM `zt_story` AS t1 WHERE t1.`title` like '%aa%')");

