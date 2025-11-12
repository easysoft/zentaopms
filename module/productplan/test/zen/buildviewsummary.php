#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildViewSummary();
timeout=0
cid=0

- 执行productplanTest模块的buildViewSummaryTest方法，参数是$stories1  @本页共 <strong>0</strong> 个，<strong>0</strong> 个， <strong>0</strong> 个，预计 <strong>0</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。
- 执行productplanTest模块的buildViewSummaryTest方法，参数是$stories2  @本页共 <strong>0</strong> 个，<strong>0</strong> 个， <strong>2</strong> 个，预计 <strong>8</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。
- 执行productplanTest模块的buildViewSummaryTest方法，参数是$stories3  @本页共 <strong>1</strong> 个，<strong>2</strong> 个， <strong>2</strong> 个，预计 <strong>30</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。
- 执行productplanTest模块的buildViewSummaryTest方法，参数是$stories4  @本页共 <strong>0</strong> 个，<strong>0</strong> 个， <strong>3</strong> 个，预计 <strong>15</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。
- 执行productplanTest模块的buildViewSummaryTest方法，参数是$stories5  @本页共 <strong>0</strong> 个，<strong>0</strong> 个， <strong>4</strong> 个，预计 <strong>18</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。
- 执行productplanTest模块的buildViewSummaryTest方法，参数是$stories6  @本页共 <strong>0</strong> 个，<strong>1</strong> 个， <strong>1</strong> 个，预计 <strong>15</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。
- 执行productplanTest模块的buildViewSummaryTest方法，参数是$stories7  @本页共 <strong>0</strong> 个，<strong>0</strong> 个， <strong>1</strong> 个，预计 <strong>0</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productplanTest = new productplanZenTest();

// 准备测试数据
// 测试场景1: 空数组
$stories1 = array();

// 测试场景2: 只包含story类型,非父需求
$stories2 = array();
$story1 = new stdClass();
$story1->id = 1;
$story1->type = 'story';
$story1->isParent = '0';
$story1->estimate = 5.0;
$story1->status = 'active';
$story1->closedReason = '';
$stories2[] = $story1;

$story2 = new stdClass();
$story2->id = 2;
$story2->type = 'story';
$story2->isParent = '0';
$story2->estimate = 3.0;
$story2->status = 'active';
$story2->closedReason = '';
$stories2[] = $story2;

// 测试场景3: 包含story/requirement/epic三种类型
$stories3 = array();
$epic1 = new stdClass();
$epic1->id = 10;
$epic1->type = 'epic';
$epic1->isParent = '0';
$epic1->estimate = 10.0;
$epic1->status = 'active';
$epic1->closedReason = '';
$stories3[] = $epic1;

$requirement1 = new stdClass();
$requirement1->id = 20;
$requirement1->type = 'requirement';
$requirement1->isParent = '0';
$requirement1->estimate = 8.0;
$requirement1->status = 'active';
$requirement1->closedReason = '';
$stories3[] = $requirement1;

$requirement2 = new stdClass();
$requirement2->id = 21;
$requirement2->type = 'requirement';
$requirement2->isParent = '0';
$requirement2->estimate = 6.0;
$requirement2->status = 'active';
$requirement2->closedReason = '';
$stories3[] = $requirement2;

$story31 = new stdClass();
$story31->id = 30;
$story31->type = 'story';
$story31->isParent = '0';
$story31->estimate = 4.0;
$story31->status = 'active';
$story31->closedReason = '';
$stories3[] = $story31;

$story32 = new stdClass();
$story32->id = 31;
$story32->type = 'story';
$story32->isParent = '0';
$story32->estimate = 2.0;
$story32->status = 'active';
$story32->closedReason = '';
$stories3[] = $story32;

// 测试场景4: 包含父需求
$stories4 = array();
$parentStory = new stdClass();
$parentStory->id = 40;
$parentStory->type = 'story';
$parentStory->isParent = '1';
$parentStory->estimate = 20.0;
$parentStory->status = 'active';
$parentStory->closedReason = '';
$stories4[] = $parentStory;

$childStory1 = new stdClass();
$childStory1->id = 41;
$childStory1->type = 'story';
$childStory1->isParent = '0';
$childStory1->estimate = 8.0;
$childStory1->status = 'active';
$childStory1->closedReason = '';
$stories4[] = $childStory1;

$childStory2 = new stdClass();
$childStory2->id = 42;
$childStory2->type = 'story';
$childStory2->isParent = '0';
$childStory2->estimate = 7.0;
$childStory2->status = 'active';
$childStory2->closedReason = '';
$stories4[] = $childStory2;

// 测试场景5: 包含已关闭需求
$stories5 = array();
$closedStory1 = new stdClass();
$closedStory1->id = 50;
$closedStory1->type = 'story';
$closedStory1->isParent = '0';
$closedStory1->estimate = 5.0;
$closedStory1->status = 'closed';
$closedStory1->closedReason = 'done';
$stories5[] = $closedStory1;

$closedStory2 = new stdClass();
$closedStory2->id = 51;
$closedStory2->type = 'story';
$closedStory2->isParent = '0';
$closedStory2->estimate = 3.0;
$closedStory2->status = 'closed';
$closedStory2->closedReason = 'postponed';
$stories5[] = $closedStory2;

$closedStory3 = new stdClass();
$closedStory3->id = 52;
$closedStory3->type = 'story';
$closedStory3->isParent = '0';
$closedStory3->estimate = 4.0;
$closedStory3->status = 'closed';
$closedStory3->closedReason = 'canceled';
$stories5[] = $closedStory3;

$activeStory = new stdClass();
$activeStory->id = 53;
$activeStory->type = 'story';
$activeStory->isParent = '0';
$activeStory->estimate = 6.0;
$activeStory->status = 'active';
$activeStory->closedReason = '';
$stories5[] = $activeStory;

// 测试场景6: 混合场景 - requirement不计入覆盖率
$stories6 = array();
$req1 = new stdClass();
$req1->id = 60;
$req1->type = 'requirement';
$req1->isParent = '0';
$req1->estimate = 10.0;
$req1->status = 'active';
$req1->closedReason = '';
$stories6[] = $req1;

$story61 = new stdClass();
$story61->id = 61;
$story61->type = 'story';
$story61->isParent = '0';
$story61->estimate = 5.0;
$story61->status = 'active';
$story61->closedReason = '';
$stories6[] = $story61;

// 测试场景7: 父需求不计入覆盖率
$stories7 = array();
$parentStory2 = new stdClass();
$parentStory2->id = 70;
$parentStory2->type = 'story';
$parentStory2->isParent = '1';
$parentStory2->estimate = 20.0;
$parentStory2->status = 'active';
$parentStory2->closedReason = '';
$stories7[] = $parentStory2;

r($productplanTest->buildViewSummaryTest($stories1)) && p() && e('本页共 <strong>0</strong> 个，<strong>0</strong> 个， <strong>0</strong> 个，预计 <strong>0</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');
r($productplanTest->buildViewSummaryTest($stories2)) && p() && e('本页共 <strong>0</strong> 个，<strong>0</strong> 个， <strong>2</strong> 个，预计 <strong>8</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');
r($productplanTest->buildViewSummaryTest($stories3)) && p() && e('本页共 <strong>1</strong> 个，<strong>2</strong> 个， <strong>2</strong> 个，预计 <strong>30</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');
r($productplanTest->buildViewSummaryTest($stories4)) && p() && e('本页共 <strong>0</strong> 个，<strong>0</strong> 个， <strong>3</strong> 个，预计 <strong>15</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');
r($productplanTest->buildViewSummaryTest($stories5)) && p() && e('本页共 <strong>0</strong> 个，<strong>0</strong> 个， <strong>4</strong> 个，预计 <strong>18</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');
r($productplanTest->buildViewSummaryTest($stories6)) && p() && e('本页共 <strong>0</strong> 个，<strong>1</strong> 个， <strong>1</strong> 个，预计 <strong>15</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');
r($productplanTest->buildViewSummaryTest($stories7)) && p() && e('本页共 <strong>0</strong> 个，<strong>0</strong> 个， <strong>1</strong> 个，预计 <strong>0</strong> 个工时， 用例覆盖率 <strong>0%</strong> 。');