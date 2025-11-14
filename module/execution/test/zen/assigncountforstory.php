#!/usr/bin/env php
<?php

/**

title=测试 executionZen::assignCountForStory();
timeout=0
cid=16402

- 执行executionZenTest模块的assignCountForStoryTest方法，参数是1, $normalStories, 'story' 第summary条的storyCount属性 @3
- 执行executionZenTest模块的assignCountForStoryTest方法，参数是1, $normalStories, 'requirement' 第summary条的storyCount属性 @3
- 执行executionZenTest模块的assignCountForStoryTest方法，参数是2, $emptyStories, 'story' 第summary条的storyCount属性 @0
- 执行executionZenTest模块的assignCountForStoryTest方法，参数是1, $singleStory, 'story' 第summary条的storyCount属性 @1
- 执行executionZenTest模块的assignCountForStoryTest方法，参数是3, $normalStories, 'epic' 第summary条的storyCount属性 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('project')->loadYaml('project_assigncountforstory', false, 2)->gen(5);
zenData('story')->loadYaml('story_assigncountforstory', false, 2)->gen(10);
zenData('task')->loadYaml('task_assigncountforstory', false, 2)->gen(15);
zenData('bug')->loadYaml('bug_assigncountforstory', false, 2)->gen(12);
zenData('case')->loadYaml('case_assigncountforstory', false, 2)->gen(10);

su('admin');

$executionZenTest = new executionZenTest();

/* 创建测试用的需求对象 */
$story1 = new stdclass();
$story1->id = 1;
$story1->title = '需求1';
$story1->children = array();

$story2 = new stdclass();
$story2->id = 2;
$story2->title = '需求2';
$child1 = new stdclass();
$child1->id = 11;
$child1->title = '子需求1';
$story2->children = array($child1);

$story3 = new stdclass();
$story3->id = 3;
$story3->title = '需求3';
$story3->children = array();

$normalStories = array($story1, $story2, $story3);
$emptyStories = array();
$singleStory = array($story1);

r($executionZenTest->assignCountForStoryTest(1, $normalStories, 'story')) && p('summary:storyCount') && e('3');
r($executionZenTest->assignCountForStoryTest(1, $normalStories, 'requirement')) && p('summary:storyCount') && e('3');
r($executionZenTest->assignCountForStoryTest(2, $emptyStories, 'story')) && p('summary:storyCount') && e('0');
r($executionZenTest->assignCountForStoryTest(1, $singleStory, 'story')) && p('summary:storyCount') && e('1');
r($executionZenTest->assignCountForStoryTest(3, $normalStories, 'epic')) && p('summary:storyCount') && e('3');