#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('productplan')->config('productplan')->gen(5);
zdTable('user')->gen(5);
su('admin');

$planstory = zdTable('planstory');
$planstory->story->range('1-5');
$planstory->plan->range('1');
$planstory->gen(5);

$story = zdTable('story');
$story->plan->range(',1,');
$story->gen(5);

$bug = zdTable('bug');
$bug->plan->range('1');
$bug->gen(5);

/**

title=测试productplanModel->transferStoriesAndBugs();
timeout=0
cid=1

*/

$planIdList = array(1, 2, 3);

$planTester = new productPlan();
r($planTester->transferStoriesAndBugsTest($planIdList[0])) && p() && e('1'); // 测试转移父计划下父计划的需求和bug
r($planTester->transferStoriesAndBugsTest($planIdList[1])) && p() && e('2'); // 测试转移父计划下子计划的需求和bug
r($planTester->transferStoriesAndBugsTest($planIdList[2])) && p() && e('2'); // 测试转移普通计划下父计划的需求和bug
