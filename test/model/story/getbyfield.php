<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getByField();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');
$byAssignedTo = $tester->story->getByField(1, 0, array(), 'assignedTo', 'admin', 'story', 'id_desc');
$byOpenedBy   = $tester->story->getByField(2, 0, array(), 'openedBy', 'admin', 'story', 'id_asc');
$byReviewedBy = $tester->story->getByField(3, 0, array(), 'reviewedBy', 'admin', 'story', 'id_desc');
$byReviewBy   = $tester->story->getByField(4, 0, array(), 'reviewBy', 'admin', 'story', 'id_desc');
$byClosedBy   = $tester->story->getByField(5, 0, array(), 'closedBy', 'admin', 'story', 'id_desc');
$byStatus     = $tester->story->getByField(6, 0, array(), 'status', 'active', 'story', 'id_desc');
$byPlan       = $tester->story->getByField(7, 0, array(), 'plan', '0', 'story', 'id_desc');

r($byAssignedTo) && p() && e();
r($byOpenedBy)   && p() && e();
r($byReviewedBy) && p() && e();
r($byReviewBy)   && p() && e();
r($byClosedBy)   && p() && e();
r($byStatus)     && p() && e();
r($byPlan)       && p() && e();
