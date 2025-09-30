#!/usr/bin/env php
<?php
/**

title=测试 myZen::showWorkCount();
timeout=0
cid=1

- 执行$count @14
- 执行$count['task'] @0
- 执行$count['story'] @2
- 执行$count['bug'] @10
- 执行$count['case'] @3
- 执行$count['testtask'] @0
- 执行$count['requirement'] @2
- 执行$count['epic'] @1
- 执行$count['issue'] @0
- 执行$count['risk'] @2
- 执行$count['reviewissue'] @0
- 执行$count['qa'] @0
- 执行$count['meeting'] @0
- 执行$count['ticket'] @0
- 执行$count['feedback'] @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/myzen.unittest.class.php';

zenData('task')->gen(10);
$story = zenData('story');
$story->type->range('story,requirement,epic');
$story->gen(30);
zenData('bug')->gen(10);
zenData('case')->gen(10);
zenData('testtask')->gen(10);
zenData('risk')->gen(10);
$reviewissue = zenData('reviewissue');
$reviewissue->project->range('1');
$reviewissue->review->range('1-5');
$reviewissue->type->range('review');
$reviewissue->opinionDate->range('`2025-01-01`');
$reviewissue->resolutionDate->range('`2025-01-01`');
$reviewissue->createdDate->range('`2025-01-01`');
$reviewissue->gen(5);
zenData('nc')->gen(0);
zenData('auditplan')->gen(10);
zenData('meeting')->gen(10);
zenData('feedback')->gen(10);
zenData('ticket')->gen(10);
zenData('demand')->gen(10);
su('admin');

global $config, $tester;
$config->URAndSR  = 1;
$config->enableER = 1;
$config->edition  = 'ipd';

$tester->app->rawModule = 'my';
$tester->app->rawMethod = 'work';
$tester->app->loadClass('pager', true);
$pager = pager::init(0, 10, 1);

$myTester = new myZenTest();
$count    = $myTester->showWorkCountNotInOpenTest($pager);
r(count($count))         && p() && e('7');
r($count['issue'])       && p() && e('0');
r($count['risk'])        && p() && e('1');
r($count['reviewissue']) && p() && e('0');
r($count['qa'])          && p() && e('0');
r($count['meeting'])     && p() && e('0');
r($count['ticket'])      && p() && e('0');
r($count['feedback'])    && p() && e('0');