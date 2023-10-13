#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$action = zdTable('action');
$action->action->range('open,closed,reviewrejected,closedbysystem,synctwins');
$action->objectType->range('story');
$action->objectID->range('1-100');
$action->execution->range('0');
$action->extra->range('``,2|closed,``,active,6|reviewing,``{1000}');
$action->gen(100);
zdTable('story')->gen(50);
$storyreview = zdTable('storyreview');
$storyreview->story->range('1-1000');
$storyreview->gen(100);


/**

title=测试 storyModel->activate();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');

r($storyModel->getActivateStatus(1))   && p() && e('active');
r($storyModel->getActivateStatus(2))   && p() && e('active');
r($storyModel->getActivateStatus(3))   && p() && e('active');
r($storyModel->getActivateStatus(4))   && p() && e('changing');
r($storyModel->getActivateStatus(5))   && p() && e('active');
r($storyModel->getActivateStatus(6))   && p() && e('active');
r($storyModel->getActivateStatus(100)) && p() && e('active');
