#!/usr/bin/env php
<?php

/**

title=测试 storyModel->activate();
cid=0

- 执行storyModel模块的setStatusByReviewResult方法，参数是$story, $oldStory, 'pass'
 - 属性status @active
 - 属性finalResult @pass
- 执行storyModel模块的setStatusByReviewResult方法，参数是$story, $oldStory, 'clarify'
 - 属性status @changing
 - 属性finalResult @clarify
- 执行storyModel模块的setStatusByReviewResult方法，参数是$story, $oldStory, 'clarify'
 - 属性status @draft
 - 属性finalResult @clarify
- 执行storyModel模块的setStatusByReviewResult方法，参数是$story, $oldStory, 'revert'
 - 属性status @active
 - 属性version @2
 - 属性finalResult @revert
- 执行storyModel模块的setStatusByReviewResult方法，参数是$story, $oldStory, 'reject', 'done'
 - 属性status @closed
 - 属性stage @released
 - 属性finalResult @reject
- 执行storyModel模块的setStatusByReviewResult方法，参数是$story, $oldStory, 'reject', 'cancel'
 - 属性status @closed
 - 属性stage @released
- 执行storyModel模块的setStatusByReviewResult方法，参数是$story, $oldStory, 'reject', 'cancel'
 - 属性status @closed
 - 属性stage @closed

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('story')->gen(20);
su('admin');

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
