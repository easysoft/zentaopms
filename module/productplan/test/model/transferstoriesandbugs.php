#!/usr/bin/env php
<?php
/**

title=测试productplanModel->transferStoriesAndBugs();
timeout=0
cid=17647

- 测试转移父计划下父计划的需求和bug @1
- 测试转移父计划下子计划的需求和bug @2
- 测试转移普通计划下父计划的需求和bug @2
- 测试转移普通计划下父计划的需求和bug @2
- 测试转移普通计划下父计划的需求和bug @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

zenData('productplan')->loadYaml('productplan')->gen(5);
zenData('user')->gen(5);
su('admin');

$planstory = zenData('planstory');
$planstory->story->range('1-5');
$planstory->plan->range('1');
$planstory->gen(5);

$story = zenData('story');
$story->plan->range(',1,');
$story->gen(5);

$bug = zenData('bug');
$bug->plan->range('1');
$bug->gen(5);

$planTester = new productPlan();
r($planTester->transferStoriesAndBugsTest(1)) && p() && e('1'); // 测试转移父计划下父计划的需求和bug
r($planTester->transferStoriesAndBugsTest(2)) && p() && e('2'); // 测试转移父计划下子计划的需求和bug
r($planTester->transferStoriesAndBugsTest(3)) && p() && e('2'); // 测试转移普通计划下父计划的需求和bug
r($planTester->transferStoriesAndBugsTest(4)) && p() && e('2'); // 测试转移普通计划下父计划的需求和bug
r($planTester->transferStoriesAndBugsTest(5)) && p() && e('2'); // 测试转移普通计划下父计划的需求和bug
