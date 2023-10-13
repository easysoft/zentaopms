#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$action = zdTable('action');
$action->objectType->range('story');
$action->objectID->range('1-2');
$action->execution->range('0');
$action->gen(4);
$history = zdTable('history');
$history->action->range('1-4');
$history->field->range('reviewer,reviewers');
$history->old->range('``');
$history->new->range('admin,dev1,dev2,dev3');
$history->gen(4);

/**

title=测试 storyModel->getlastreviewer();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');

r($tester->story->getLastReviewer(0)) && p() && e('0');
r($tester->story->getLastReviewer(1)) && p() && e('dev2');
