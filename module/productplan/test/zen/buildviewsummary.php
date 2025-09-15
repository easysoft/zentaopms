#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildViewSummary();
timeout=0
cid=0

- 执行productplanZenTest模块的buildViewSummaryTest方法，参数是$emptyStories  @本页共 <strong>0</strong> 个史诗，<strong>0</strong> 个用户需求， <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。
- 执行productplanZenTest模块的buildViewSummaryTest方法，参数是$epicStories  @本页共 <strong>2</strong> 个史诗，<strong>0</strong> 个用户需求， <strong>0</strong> 个研发需求，预计 <strong>8</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。
- 执行productplanZenTest模块的buildViewSummaryTest方法，参数是$mixedStories  @本页共 <strong>1</strong> 个史诗，<strong>1</strong> 个用户需求， <strong>1</strong> 个研发需求，预计 <strong>10</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。
- 执行productplanZenTest模块的buildViewSummaryTest方法，参数是$parentStories  @本页共 <strong>0</strong> 个史诗，<strong>0</strong> 个用户需求， <strong>2</strong> 个研发需求，预计 <strong>3</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。
- 执行productplanZenTest模块的buildViewSummaryTest方法，参数是$closedStories  @本页共 <strong>0</strong> 个史诗，<strong>0</strong> 个用户需求， <strong>3</strong> 个研发需求，预计 <strong>10</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplanzen.unittest.class.php';

zenData('user')->gen(5);
zenData('story')->gen(0);

su('admin');

global $app;
$app->rawModule  = 'productplan';
$app->rawMethod  = 'view';
$app->moduleName = 'productplan';
$app->methodName = 'view';

$productplanZenTest = new productplanZenTest();

// 模拟lang对象，设置术语
$productplanZenTest->objectZen->lang->ERCommon = '史诗';
$productplanZenTest->objectZen->lang->URCommon = '用户需求';
$productplanZenTest->objectZen->lang->SRCommon = '研发需求';
$productplanZenTest->objectZen->lang->hourCommon = '工时';

// 步骤1：测试空数组情况
$emptyStories = array();
r($productplanZenTest->buildViewSummaryTest($emptyStories)) && p() && e('本页共 <strong>0</strong> 个史诗，<strong>0</strong> 个用户需求， <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');

// 步骤2：测试仅包含史诗类型的需求
$epicStories = array();
$epic1 = new stdClass();
$epic1->type = 'epic';
$epic1->isParent = '0';
$epic1->estimate = 5.0;
$epic1->status = 'active';
$epic1->id = 1;
$epicStories[] = $epic1;

$epic2 = new stdClass();
$epic2->type = 'epic';
$epic2->isParent = '0';
$epic2->estimate = 3.0;
$epic2->status = 'active';
$epic2->id = 2;
$epicStories[] = $epic2;

r($productplanZenTest->buildViewSummaryTest($epicStories)) && p() && e('本页共 <strong>2</strong> 个史诗，<strong>0</strong> 个用户需求， <strong>0</strong> 个研发需求，预计 <strong>8</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');

// 步骤3：测试包含不同类型需求的混合场景
$mixedStories = array();
$epic = new stdClass();
$epic->type = 'epic';
$epic->isParent = '0';
$epic->estimate = 5.0;
$epic->status = 'active';
$epic->id = 3;
$mixedStories[] = $epic;

$requirement = new stdClass();
$requirement->type = 'requirement';
$requirement->isParent = '0';
$requirement->estimate = 3.0;
$requirement->status = 'active';
$requirement->id = 4;
$mixedStories[] = $requirement;

$story = new stdClass();
$story->type = 'story';
$story->isParent = '0';
$story->estimate = 2.0;
$story->status = 'active';
$story->id = 5;
$mixedStories[] = $story;

r($productplanZenTest->buildViewSummaryTest($mixedStories)) && p() && e('本页共 <strong>1</strong> 个史诗，<strong>1</strong> 个用户需求， <strong>1</strong> 个研发需求，预计 <strong>10</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');

// 步骤4：测试包含父需求的场景
$parentStories = array();
$parentStory = new stdClass();
$parentStory->type = 'story';
$parentStory->isParent = '1';
$parentStory->estimate = 10.0;
$parentStory->status = 'active';
$parentStory->id = 6;
$parentStories[] = $parentStory;

$childStory = new stdClass();
$childStory->type = 'story';
$childStory->isParent = '0';
$childStory->estimate = 3.0;
$childStory->status = 'active';
$childStory->id = 7;
$parentStories[] = $childStory;

r($productplanZenTest->buildViewSummaryTest($parentStories)) && p() && e('本页共 <strong>0</strong> 个史诗，<strong>0</strong> 个用户需求， <strong>2</strong> 个研发需求，预计 <strong>3</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');

// 步骤5：测试包含已关闭需求的场景
$closedStories = array();
$activeStory = new stdClass();
$activeStory->type = 'story';
$activeStory->isParent = '0';
$activeStory->estimate = 5.0;
$activeStory->status = 'active';
$activeStory->id = 8;
$closedStories[] = $activeStory;

$closedStory = new stdClass();
$closedStory->type = 'story';
$closedStory->isParent = '0';
$closedStory->estimate = 3.0;
$closedStory->status = 'closed';
$closedStory->closedReason = 'done';
$closedStory->id = 9;
$closedStories[] = $closedStory;

$canceledStory = new stdClass();
$canceledStory->type = 'story';
$canceledStory->isParent = '0';
$canceledStory->estimate = 2.0;
$canceledStory->status = 'closed';
$canceledStory->closedReason = 'cancel';
$canceledStory->id = 10;
$closedStories[] = $canceledStory;

r($productplanZenTest->buildViewSummaryTest($closedStories)) && p() && e('本页共 <strong>0</strong> 个史诗，<strong>0</strong> 个用户需求， <strong>3</strong> 个研发需求，预计 <strong>10</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');