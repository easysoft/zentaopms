#!/usr/bin/env php
<?php

/**

title=测试 bugZen::operateAfterBatchEdit();
timeout=0
cid=0

- 步骤1：active转resolved，记录积分 @score_recorded
- 步骤2：active转closed，无积分 @0
- 步骤3：resolved转closed，无积分 @0
- 步骤4：有反馈的bug状态更新（开源版本不更新feedback） @score_recorded
- 步骤5：验证积分记录行为 @score_recorded

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->loadYaml('bug', false, 2)->gen(10);
zenData('user')->loadYaml('user', false, 2)->gen(10);
zenData('score')->loadYaml('score', false, 2)->gen(0);
zenData('feedback')->loadYaml('feedback', false, 2)->gen(5);

su('admin');

$bugTest = new bugTest();

// 创建测试用的bug对象
$activeBug = new stdClass();
$activeBug->id = 1;
$activeBug->status = 'active';
$activeBug->resolvedBy = '';
$activeBug->feedback = 0;

$resolvedBug = new stdClass();
$resolvedBug->id = 1;
$resolvedBug->status = 'resolved';
$resolvedBug->resolvedBy = 'admin';
$resolvedBug->feedback = 0;

$closedBug = new stdClass();
$closedBug->id = 1;
$closedBug->status = 'closed';
$closedBug->resolvedBy = 'admin';
$closedBug->feedback = 0;

$bugWithFeedback = new stdClass();
$bugWithFeedback->id = 2;
$bugWithFeedback->status = 'resolved';
$bugWithFeedback->resolvedBy = 'admin';
$bugWithFeedback->feedback = 1;

$oldBugWithFeedback = new stdClass();
$oldBugWithFeedback->id = 2;
$oldBugWithFeedback->status = 'active';
$oldBugWithFeedback->resolvedBy = '';
$oldBugWithFeedback->feedback = 1;

r($bugTest->operateAfterBatchEditTest($resolvedBug, $activeBug)) && p() && e('score_recorded');        // 步骤1：active转resolved，记录积分
r($bugTest->operateAfterBatchEditTest($closedBug, $activeBug)) && p() && e('0');                  // 步骤2：active转closed，无积分
r($bugTest->operateAfterBatchEditTest($closedBug, $resolvedBug)) && p() && e('0');                // 步骤3：resolved转closed，无积分
r($bugTest->operateAfterBatchEditTest($bugWithFeedback, $oldBugWithFeedback)) && p() && e('score_recorded'); // 步骤4：有反馈的bug状态更新（开源版本不更新feedback）
r($bugTest->operateAfterBatchEditTest($resolvedBug, $activeBug)) && p() && e('score_recorded');   // 步骤5：验证积分记录行为