#!/usr/bin/env php
<?php

/**

title=测试 storyModel->doUpdateLinkStories();
timeout=0
cid=18621

- 执行$storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(2)->fetch()属性linkStories @1
- 执行$storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(4)->fetch()属性linkStories @~~
- 执行$storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(2)->fetch()属性linkRequirements @1
- 执行$storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(4)->fetch()属性linkRequirements @~~
- 执行$storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(2)->fetch()属性linkStories @1
- 执行$storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(4)->fetch()属性linkStories @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

$story = zenData('story');
$story->product->range('1');
$story->version->range('1');
$story->linkStories->range('0,0,0,1,0');
$story->linkRequirements->range('0,0,0,1,0');
$story->gen(5);

global $tester;
$storyModel = $tester->loadModel('story');

$story = new stdclass();
$story->linkStories = '2,3';
$story->linkRequirements = '2,3';

$oldStory = new stdclass();
$oldStory->type = 'story';
$oldStory->linkStories = '3,4';
$oldStory->linkRequirements = '3,4';

$storyModel->doUpdateLinkStories(1, $story, $oldStory);
r($storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(2)->fetch()) && p('linkStories') && e('1');
r($storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(4)->fetch()) && p('linkStories') && e('~~');

$oldStory->type = 'requirement';
$storyModel->doUpdateLinkStories(1, $story, $oldStory);
r($storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(2)->fetch()) && p('linkRequirements') && e('1');
r($storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(4)->fetch()) && p('linkRequirements') && e('~~');

$oldStory->type = 'epic';
$storyModel->doUpdateLinkStories(1, $story, $oldStory);
r($storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(2)->fetch()) && p('linkStories') && e('1');
r($storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(4)->fetch()) && p('linkStories') && e('~~');