#!/usr/bin/env php
<?php

/**

title=测试 storyModel->buildStoryTrack();
cid=18603

- 执行$track @0
- 执行$track->cases @5
- 执行$track->bugs @5
- 执行$track->tasks @5
- 执行$track->designs @0
- 执行$track->revisions @0
- 执行$track->cases @5
- 执行$track->bugs @5
- 执行$track->tasks @1
- 执行$track->designs @0
- 执行$track->revisions @0
- 执行$track->cases @5
- 执行$track->bugs @5
- 执行$track->tasks @5
- 执行$track->designs @5
- 执行$track->revisions @5
- 执行$track->cases @5
- 执行$track->bugs @5
- 执行$track->tasks @1
- 执行$track->designs @5
- 执行$track->revisions @5

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";

$case = zenData('case');
$case->story->range(1);
$case->gen(5);

$bug = zenData('bug');
$bug->story->range(1);
$bug->gen(5);

$task = zenData('task');
$task->story->range(1);
$task->gen(5);

$design = zenData('design');
$design->story->range(1);
$design->gen(5);

$relation = zenData('relation');
$relation->AID->range('1-5');
$relation->AType->range('design');
$relation->BID->range('1-5');
$relation->BType->range('commit');
$relation->gen(5);

zenData('repohistory')->gen(5);

global $tester;
$storyModel = $tester->loadModel('story');

$track = $storyModel->buildStoryTrack(new stdclass());
r(count(get_object_vars($track))) && p() && e('0');

$storyModel->config->edition = 'open';
$story = new stdclass();
$story->id     = 1;
$story->parent = 0;
$story->title  = 'test';
$track = $storyModel->buildStoryTrack($story);
r(count($track->cases))     && p() && e('5');
r(count($track->bugs))      && p() && e('5');
r(count($track->tasks))     && p() && e('5');
r(isset($track->designs))   && p() && e('0');
r(isset($track->revisions)) && p() && e('0');

$track = $storyModel->buildStoryTrack($story, 11);
r(count($track->cases))     && p() && e('5');
r(count($track->bugs))      && p() && e('5');
r(count($track->tasks))     && p() && e('1');
r(isset($track->designs))   && p() && e('0');
r(isset($track->revisions)) && p() && e('0');

$storyModel->config->edition = 'max';
$track = $storyModel->buildStoryTrack($story);
r(count($track->cases))     && p() && e('5');
r(count($track->bugs))      && p() && e('5');
r(count($track->tasks))     && p() && e('5');
r(count($track->designs))   && p() && e('5');
r(count($track->revisions)) && p() && e('5');

$track = $storyModel->buildStoryTrack($story, 11);
r(count($track->cases))     && p() && e('5');
r(count($track->bugs))      && p() && e('5');
r(count($track->tasks))     && p() && e('1');
r(count($track->designs))   && p() && e('5');
r(count($track->revisions)) && p() && e('5');
