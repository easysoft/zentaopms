#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

/**

title=测试 storyModel->getReviewerPairs();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->getReviewResult(array())) && p() && e('pass');

$storyModel->config->story->reviewRules = 'allpass';
r($storyModel->getReviewResult(array('pass', 'pass'))) && p() && e('pass');
r($storyModel->getReviewResult(array('pass', 'pass', 'reject'))) && p() && e('reject');
r($storyModel->getReviewResult(array('clarify', 'clarify', 'clarify', 'pass'))) && p() && e('clarify');
r($storyModel->getReviewResult(array('revert', 'revert', 'revert', 'pass'))) && p() && e('revert');
r($storyModel->getReviewResult(array('reject', 'reject', 'reject', 'pass'))) && p() && e('reject');

r($storyModel->getReviewResult(array('clarify', '', '', ''))) && p() && e('clarify');
r($storyModel->getReviewResult(array('revert',  '', '', ''))) && p() && e('revert');
r($storyModel->getReviewResult(array('reject',  '', '', ''))) && p() && e('reject');

$storyModel->config->story->reviewRules = 'halfpass';
r($storyModel->getReviewResult(array('pass', 'pass', 'reject'))) && p() && e('pass');

