#!/usr/bin/env php
<?php

/**

title=测试 convertTao::updateRelatesObject();
timeout=0
cid=15874

- 步骤1：任务关联需求正常情况 @1
- 步骤2：需求关联任务正常情况 @1
- 步骤3：需求关联缺陷正常情况 @1
- 步骤4：缺陷关联需求正常情况 @1
- 步骤5：需求之间关联和缺陷之间关联 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-5');
$task->name->range('任务{1-5}');
$task->story->range('0{5}');
$task->execution->range('1{5}');
$task->status->range('wait{5}');
$task->deleted->range('0{5}');
$task->gen(5);

$story = zenData('story');
$story->id->range('1-5');
$story->title->range('需求{1-5}');
$story->linkStories->range('');
$story->status->range('active{5}');
$story->stage->range('wait{5}');
$story->deleted->range('0{5}');
$story->gen(5);

$bug = zenData('bug');
$bug->id->range('1-5');
$bug->title->range('缺陷{1-5}');
$bug->story->range('0{5}');
$bug->storyVersion->range('1{5}');
$bug->relatedBug->range('');
$bug->status->range('active{5}');
$bug->deleted->range('0{5}');
$bug->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->updateRelatesObjectTest(array('jira_task_1' => 'jira_story_1'), array('jira_task_1' => array('BType' => 'ztask', 'BID' => '1'), 'jira_story_1' => array('BType' => 'zstory', 'BID' => '2')))) && p() && e('1'); // 步骤1：任务关联需求正常情况
r($convertTest->updateRelatesObjectTest(array('jira_story_2' => 'jira_task_2'), array('jira_story_2' => array('BType' => 'zstory', 'BID' => '3'), 'jira_task_2' => array('BType' => 'ztask', 'BID' => '4')))) && p() && e('1'); // 步骤2：需求关联任务正常情况
r($convertTest->updateRelatesObjectTest(array('jira_story_3' => 'jira_bug_1'), array('jira_story_3' => array('BType' => 'zstory', 'BID' => '1'), 'jira_bug_1' => array('BType' => 'zbug', 'BID' => '1')))) && p() && e('1'); // 步骤3：需求关联缺陷正常情况
r($convertTest->updateRelatesObjectTest(array('jira_bug_2' => 'jira_story_4'), array('jira_bug_2' => array('BType' => 'zbug', 'BID' => '2'), 'jira_story_4' => array('BType' => 'zstory', 'BID' => '2')))) && p() && e('1'); // 步骤4：缺陷关联需求正常情况
r($convertTest->updateRelatesObjectTest(array('jira_story_5' => 'jira_story_6', 'jira_bug_3' => 'jira_bug_4'), array('jira_story_5' => array('BType' => 'zstory', 'BID' => '3'), 'jira_story_6' => array('BType' => 'zstory', 'BID' => '4'), 'jira_bug_3' => array('BType' => 'zbug', 'BID' => '3'), 'jira_bug_4' => array('BType' => 'zbug', 'BID' => '4')))) && p() && e('1'); // 步骤5：需求之间关联和缺陷之间关联