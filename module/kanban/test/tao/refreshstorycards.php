#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshStoryCards();
timeout=0
cid=16991

- 执行kanbanTest模块的refreshStoryCardsTest方法，参数是$cardPairs0, 999, ''  @10
- 执行kanbanTest模块的refreshStoryCardsTest方法，参数是$cardPairs1, 101, ''
 - 属性backlog @
- 执行kanbanTest模块的refreshStoryCardsTest方法，参数是$cardPairs2, 101, '' 属性designing @~~
- 执行kanbanTest模块的refreshStoryCardsTest方法，参数是$cardPairs3, 101, ''
 - 属性designed @
- 执行kanbanTest模块的refreshStoryCardsTest方法，参数是$cardPairs4, 101, '' 属性developing @~~
- 执行kanbanTest模块的refreshStoryCardsTest方法，参数是$cardPairs5, 101, ''
 - 属性developed @
- 执行kanbanTest模块的refreshStoryCardsTest方法，参数是$cardPairs6, 101, '' 属性testing @~~
- 执行kanbanTest模块的refreshStoryCardsTest方法，参数是$cardPairs7, 101, ''
 - 属性tested @
- 执行kanbanTest模块的refreshStoryCardsTest方法，参数是$cardPairs8, 101, '' 属性verified @~~
- 执行kanbanTest模块的refreshStoryCardsTest方法，参数是$cardPairs9, 101, ''
 - 属性closed @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

$project = zenData('project');
$project->id->range('101,999');
$project->name->range('项目101,项目999');
$project->type->range('sprint');
$project->status->range('doing');
$project->gen(2);

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1');
$story->status->range('active');
$story->stage->range('projected{2},designing,designed,developing,developed,testing,tested,verified,closed');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$story->gen(10);

$projectStory = zenData('projectstory');
$projectStory->project->range('101');
$projectStory->product->range('1');
$projectStory->story->range('1-10');
$projectStory->gen(10);

su('admin');

$kanbanTest = new kanbanTest();

$cardPairs0 = array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'developing' => '', 'developed' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'closed' => '');
$cardPairs1 = array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'developing' => '', 'developed' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'closed' => '');
$cardPairs2 = array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'developing' => '', 'developed' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'closed' => '');
$cardPairs3 = array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'developing' => '', 'developed' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'closed' => '');
$cardPairs4 = array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'developing' => '', 'developed' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'closed' => '');
$cardPairs5 = array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'developing' => '', 'developed' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'closed' => '');
$cardPairs6 = array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'developing' => '', 'developed' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'closed' => '');
$cardPairs7 = array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'developing' => '', 'developed' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'closed' => '');
$cardPairs8 = array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'developing' => '', 'developed' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'closed' => '');
$cardPairs9 = array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'developing' => '', 'developed' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'closed' => '');

r(count($kanbanTest->refreshStoryCardsTest($cardPairs0, 999, ''))) && p() && e('10');
r($kanbanTest->refreshStoryCardsTest($cardPairs1, 101, '')) && p('backlog') && e(',2,');
r($kanbanTest->refreshStoryCardsTest($cardPairs2, 101, '')) && p('designing') && e('~~');
r($kanbanTest->refreshStoryCardsTest($cardPairs3, 101, '')) && p('designed') && e(',4,');
r($kanbanTest->refreshStoryCardsTest($cardPairs4, 101, '')) && p('developing') && e('~~');
r($kanbanTest->refreshStoryCardsTest($cardPairs5, 101, '')) && p('developed') && e(',6,');
r($kanbanTest->refreshStoryCardsTest($cardPairs6, 101, '')) && p('testing') && e('~~');
r($kanbanTest->refreshStoryCardsTest($cardPairs7, 101, '')) && p('tested') && e(',8,');
r($kanbanTest->refreshStoryCardsTest($cardPairs8, 101, '')) && p('verified') && e('~~');
r($kanbanTest->refreshStoryCardsTest($cardPairs9, 101, '')) && p('closed') && e(',10,');