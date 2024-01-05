#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('story')->gen(20);
su('admin');

/**

title=测试 storyModel->activate();
cid=1
pid=1

查看激活之前的需求状态 >> draft
查看激活之前的需求状态 >> active
查看激活之前的需求状态 >> closed
查看激活之前的需求状态 >> changing

查看激活之后的需求状态 >> active
查看激活之后的需求状态 >> active
查看激活之后的需求状态 >> active
查看激活之后的需求状态 >> active

*/

global $tester;
$storyModel = $tester->loadModel('story');

$story = new stdclass();
$story->id     = 1;
$story->status = 'draft';

$oldStory = new stdclass();
$oldStory->status  = 'draft';
$oldStory->version = '3';
$oldStory->twins   = '';
$oldStory->id      = 1;

r($storyModel->setStatusByReviewResult($story, $oldStory, 'pass')) && p('status,finalResult') && e('active,pass');

$story->status = 'draft';
$oldStory->changedBy = true;
r($storyModel->setStatusByReviewResult($story, $oldStory, 'clarify')) && p('status,finalResult') && e('changing,clarify');
$oldStory->changedBy = false;
r($storyModel->setStatusByReviewResult($story, $oldStory, 'clarify')) && p('status,finalResult') && e('draft,clarify');

$story->status = 'draft';
r($storyModel->setStatusByReviewResult($story, $oldStory, 'revert')) && p('status,version,finalResult') && e('active,2,revert');

r($storyModel->setStatusByReviewResult($story, $oldStory, 'reject', 'done')) && p('status,stage,finalResult') && e('closed,released,reject');
r($storyModel->setStatusByReviewResult($story, $oldStory, 'reject', 'cancel')) && p('status,stage') && e('closed,released');
unset($story->closedReason);
r($storyModel->setStatusByReviewResult($story, $oldStory, 'reject', 'cancel')) && p('status,stage') && e('closed,closed');
